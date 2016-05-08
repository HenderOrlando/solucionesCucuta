/**
 * Created by hender on 11/02/16.
 */

(function(){
    'use strict';

    /**
     * @ngdoc function
     * @name pempoApp.controller:FilemanagerCtrl
     * @description
     * # FilemanagerCtrl
     * Controller of the pempoApp
     */
    angular.module('SolucionesCucutaApp')
        .controller('FilemanagerCtrl', [
            "$rootScope",
            "pempoResource",
            "$translatePartialLoader",
            "$mdDialog",
            "$mdMedia",
            "Upload",
            "attrs",
            "$q",
            FilemanagerCtrl
        ]);

    function FilemanagerCtrl(
        $rootScope,
        pempoResource,
        $translatePartialLoader,
        $mdDialog,
        $mdMedia,
        Upload,
        attrs,
        $q
    ) {
        //console.log('ToastCtrl')
        //console.log(attrs)

        //$translatePartialLoader.addPart('filemanager');

        var
            vm              = this,
            Tipo            = pempoResource('Tipo'),
            Archivo         = pempoResource('Archivo'),
            tiposTinymce    = {
                image:  ['image'],
                media:  ['audio', 'video'],
                file:   ['all']
            },
            tiposFile       = [],
            query           = {
                populate: true
            },
            tempList        = [],
            tempQuery       = {},
            querySelected   = ''
        ;
        vm.isPortrait       = false;
        vm.title            = attrs.title || 'Pempo File Manager';
        vm.meta             = attrs.meta || false;
        vm.value            = _.isObject(attrs.value)?angular.copy(attrs.value):(attrs.value?attrs.value:false);
        vm.files            = [];
        vm.fileSelected     = vm.value || (attrs.multiple?[]:null);
        vm.hasFileSelected  = hasFileSelected;

        vm.selectFile       = selectFile;
        vm.cancel           = cancel;
        vm.ok               = ok;
        vm.getOnlySelected  = getOnlySelected;
        vm.onlySelected     = false;

        vm.goToPage         = goToPage;
        vm.getPageList      = getPageList;
        vm.pagelist         = [];
        vm.isLastPage       = isLastPage;
        vm.isFirstPage      = isFirstPage;
        vm.prevPage         = prevPage;
        vm.nextPage         = nextPage;
        vm.pagination       = {
            page: 1,    // Page actual
            total: 0,   // Total elements
            pages: 1,   // Total pages
            limit: 4,    // Elements of page,
            firstElement: 1,    // First Elements of page,
            lastElement: 1    // Last Element of page
        };

        vm.clearQuery       = clearQuery;
        vm.clearQueryModel  = false;
        vm.loadFiles        = loadFiles;
        vm.query            = {
            button: {
                label:  'buscar',
                icon:   'magnify'
            },
            label:  'buscar',
            order: 'nombre',
            model: '',
            filter: filterQuery
        };

        vm.upload = upload;

        $rootScope.addWatch(function(){
            return $mdMedia('max-width: 500px');
        },function(isPortrait){
            // true - portrait
            // true - landscape
            vm.isPortrait = isPortrait;
        }, 'portraitFilemanager');

        getTiposFile().then(function(rta){
            //console.log(rta)
            var ids = [];
            if(rta.length){
                for(var h = 0; h < rta.length; h++){
                    tiposFile = tiposFile.concat(rta[h]);
                }
                ids = tiposFile.map(function(tipo){
                    return tipo.id;
                });
                query['where'] = {
                    tipo: ids
                };
            }
            loadFiles();
        }, function(err){
            console.log(err)
        });

        function isLastPage(){
            return vm.pagination.page === vm.pagination.pages;
        }

        function isFirstPage(){
            return vm.pagination.page === 1;
        }

        function goToPage(pagenum){
            if(pagenum === true){
                loadFiles(true);
            }else{
                pagenum = pagenum || vm.pagination.page;
                if(pagenum >= 1 && pagenum <= vm.pagination.pages){
                    vm.pagination.page = pagenum;
                    loadFiles();
                }
            }
        }

        function nextPage($event){
            if(vm.pagination.page + 1 <= vm.pagination.pages){
                vm.pagination.page++;
                loadFiles();
            }
        }

        function prevPage($event){
            if(vm.pagination.page > 1){
                vm.pagination.page--;
                loadFiles();
            }
        }

        function getTiposFile(){
            var tiposPromise = [], query = {};
            return $q(function(resolve, reject){
                var tipos = [];
                if(vm.meta){
                    if(vm.meta.filetype === 'file'){
                        resolve(tipos);
                    }else{
                        for(var h = 0; h < tiposTinymce[vm.meta.filetype].length; h++) {
                            query = {
                                where: {
                                    detalles: {
                                        contains: tiposTinymce[vm.meta.filetype][h]
                                    }
                                },
                                skip: 0,
                                limit: 200,
                                sort: 'detalles ASC'
                            };
                            tiposPromise.push(Tipo.hateoas(query));
                        }
                        $q.all(tiposPromise).then(resolve, reject);
                    }
                }else{
                    resolve(tipos);
                }
            });
        }

        function hasFileSelected(file){
            if(_.isArray(vm.fileSelected)){
                return vm.fileSelected.filter(function(file_){
                        return file.id == file_.id;
                    }).length > 0;
            }
            return file.id == vm.fileSelected.id;
        }

        function selectFile($event, file){
            if(attrs.multiple === true || (_.isNumber(attrs.multiple) && attrs.multiple > 1)){
                var newList = vm.fileSelected.filter(function(val){
                    return file.id !== val.id;
                });
                //console.log(newList)
                if(newList.length !== vm.fileSelected.length){
                    vm.fileSelected = newList;
                }else{
                    if(_.isNumber(attrs.multiple)){
                        if(vm.fileSelected.length === attrs.multiple){
                            vm.fileSelected.splice(0,1);
                        }
                    }
                    vm.fileSelected.push(file);
                }
            }else{
                if(vm.fileSelected && vm.fileSelected.id === file.id){
                    vm.fileSelected = null;
                }else{
                    vm.fileSelected = file;
                }
                closeDialog()
            }
        }

        function ok($event){
            closeDialog($event, true);
        }

        function cancel($event){
            closeDialog($event, false);
        }

        function closeDialog($event, ok){
            if($event){
                $event.preventDefault();
                $event.stopPropagation();
            }
            if(ok){
                var dialog = $mdDialog.hide(vm.fileSelected);
                if(attrs.cb){
                    dialog.then(function(){
                        attrs.cb(vm.fileSelected);
                    });
                }
            }else{
                $mdDialog.hide();
            }
        }

        function getQuery(){
            var
                query_ = angular.copy(query),
                querycanonical = '',
                search = {}
            ;
            if(!_.isEmpty(vm.query.model) && angular.isString(vm.query.model)){
                vm.clearQueryModel = true;
                querycanonical = _.kebabCase(_.deburr(vm.query.model));
                search = {
                    contains: querycanonical
                };
                query_.or = [[
                    {
                        nombre:     search
                    },{
                        canonical:  search
                    },{
                        descripcion:search
                    }
                ]];
            }else{
                vm.clearQueryModel = false;
            }
            query_.page = vm.pagination.page;
            query_.limit = vm.pagination.limit;
            return query_;
        }

        function clearQuery($event){
            vm.query.model = '';
            vm.clearQueryModel = false;
            loadFiles();
        }

        function loadFiles(backList){
            if(vm.onlySelected){
                if(backList){
                    querySelected = vm.query.model;
                    vm.query.model = tempQuery;
                    vm.files = tempList;
                    tempList = [];
                    vm.onlySelected = false;
                }
            }else{
                return Archivo.hateoas(getQuery()).then(function(list){
                    var metainfo = list.$headers.metainfo;
                    if(metainfo){
                        vm.pagination = {
                            page: metainfo.page,        // Page actual
                            total: metainfo.total,      // Total elements
                            pages: metainfo.lastPage,   // Total pages
                            limit: vm.pagination.limit,  // Elements for page
                            firstElement: metainfo.start + 1,
                            lastElement: metainfo.start + metainfo.limit
                        };
                        loadPageList();
                    }
                    vm.files = list;
                    return list;
                });
            }
        }

        function loadPageList(){
            vm.pagelist = [];
            for(var h = 1; h <= vm.pagination.pages; h++){
                vm.pagelist.push(h);
            }
        }

        function getPageList(){
            loadPageList();
            //return vm.pagelist;
        }

        function filterQuery(item, index,list){
            var found = true;
            if(!_.isEmpty(vm.query.model) && angular.isString(vm.query.model)){
                //console.log(item)
                var querycanonical = _.kebabCase(_.deburr(vm.query.model));
                found = (item.nombre && item.nombre.indexOf(querycanonical) > -1) ||
                        (item.canonical && item.canonical.indexOf(querycanonical) > -1) ||
                        (item.descripcion && item.descripcion.indexOf(querycanonical) > -1)
                ;
            }
            return found;
        }

        function getOnlySelected($event){
            tempList = vm.files;
            tempQuery = vm.query.model;
            vm.query.model = querySelected;
            vm.files = _.isArray(vm.fileSelected)?vm.fileSelected:[vm.fileSelected];
            vm.onlySelected = true;
        }



        function upload(files) {
            console.log(files);
            Upload.upload({
                url: '/file-form',
                //data: {'username': $scope.username}
                file: files
            }).then(function (resp) {
                console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            }, function (resp) {
                console.log('Error status: ' + resp.status);
            }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
            });
        }
    }
})();
