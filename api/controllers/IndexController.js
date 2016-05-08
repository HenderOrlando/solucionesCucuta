/**
 * IndexController
 *
 * @description :: Server-side logic for managing indices
 * @help        :: See http://sailsjs.org/#!/documentation/concepts/Controllers
 */
var Promise = require('bluebird'),
    promisify = Promise.promisify,
    actionUtil = require('sails/lib/hooks/blueprints/actionUtil')
;

module.exports = {
	session: function(req, res){
        res.send(req.sessionID);
    },
	index: function(req, res){
        /*Menu
            .find({tipo: '5717e66041201db50a61b484'})
            .populate('submenu')
            .exec(function(err, menu){
                if(err){
                    res.negotiate(err);
                }
                return res.view({
                    menu: menu
                });
            })
        ;*/
        res.view();
    },
    findModelAction: function findModelAction(req, res) {

        if (actionUtil.parsePk(req)) {
            return require('./findOne')(req, res);
        }

        var
            params = req.params.all(),
            page = parseInt(params.page),
            query = null,
            checkPublicacion = params.model.toLowerCase() === 'publicacion'
        ;

        req.options.model = params.model.toLowerCase();

        var
            Model = actionUtil.parseModel(req),
            where = actionUtil.parseCriteria(req),
            limit = actionUtil.parseLimit(req),
            skip = actionUtil.parseSkip(req),
            sort = actionUtil.parseSort(req)
            ;
        //console.log(params)
        delete where.model;
        delete where.page;

        if(where.or && _.isString(where.or)){
            where.or = JSON.parse(where.or);
        }
        if(page){
            query = Model.find().paginate({page: page, limit: limit});
            skip = (page * limit) - limit;
            limit = (page * limit);
        }else{
            query = Model.find().limit(limit).skip(skip);
            if(skip > 0){
                page = page || (limit / (limit - skip));
            }else{
                page = 1;
            }
        }

        query = query.where(where).sort(sort);

        //query = actionUtil.populateRequest(query, req)

        if(!!params.populate){
            if(params.populate === true || params.populate === 'true'){
                query = query.populateAll();
            }else{
                try{
                    var
                        populate = JSON.parse(params.populate),
                        keysPopulate = Object.keys(populate)
                        ;
                    if(_.isArray(populate)){
                        query = query.populate(populate);
                    }else if(_.isObject(populate)){
                        for(var h = 0; h < keysPopulate.length; h++){
                            query = query.populate(keysPopulate[h], populate[keysPopulate[h]]);
                        }
                    }
                }catch(e){
                    query = query.populate([params.populate]);
                }
            }
        }

        query.exec(function(error, records) {

            if (error) {
                return res.negotiate(error);
            }
            if(checkPublicacion){
                var promises = [];
                var estado = API.Model(Estado);
                _.forEach(records, function(record){
                    if(record.hasPublicated()){
                        promises.push(estado.findOne({ canonical: 'publicado' }).then(function(estado){
                            record.estado = estado;
                            record.save();
                        }));
                    }
                });
                Promise.all(promises).then(function(){
                    returnHateoas();
                });
            }else{
                returnHateoas();
            }

            function returnHateoas(){
                Model.count(where).exec(function(error, count) {

                    if (error) {
                        return res.negotiate(error);
                    }

                    var
                        lastPage = parseInt(count/(limit - skip)),
                        nextPage = page + 1,
                        previousPage = page - 1
                        ;

                    if(count % (limit - skip) ){
                        lastPage++;
                    }
                    if(nextPage > lastPage){
                        nextPage = 1;
                    }
                    if(previousPage < 1){
                        previousPage = lastPage;
                    }

                    var metaInfo = {

                        start:  skip,
                        //end:    skip + limit,
                        //end:    skip + records.length,
                        end:    limit,
                        limit:  records.length,
                        total:  count,

                        firstPage:      1,
                        lastPage:       lastPage,
                        page:           page,
                        nextPage:       nextPage,
                        previousPage:   previousPage,

                        criteria: where,
                        sort: sort
                    };

                    if(req.isSocket){
                        var keys = records.map(function(val){ return val.id});
                        Model.watch(req);
                        Model.subscribe(req, keys);
                    }

                    //res.set('content-range', metaInfo.start + '-' + metaInfo.end + '/' + metaInfo.total);
                    res.set('metainfo', metaInfo);
                    return res.ok(records, null, null, metaInfo);
                });
            }
        });
    },
    paramsAction: function(req, res){
        var params = req.params.all();
        req.options.model = params.model;
        var
            attrs = {},
            Model = actionUtil.parseModel(req),
            attributes = Model.attributes,
            modelname = Model.identity,
            blacklist = [
                'id', 'createdAt', 'updatedAt', 'canonical',
                // Archivo
                'archivo.ext', 'archivo.size', 'archivo.src',
                // Estados
                'estado.serviciousuarios',
                // Etiquetas
                '',
                // Gastos
                'gasto.usuario',
                // Menus
                '',
                // Páginas
                '',
                // Pagos - 'serviciousuario'
                '',
                // Permisos
                '',
                // Publicaciones
                '',
                // Roles
                'rol.serviciousuario',
                // Servicios
                '',
                // Tipos
                '',
                // Usuarios
                'usuario.password', 'usuario.tokens', 'usuario.cliente',
                // Clientes
                'cliente.canonicalorganizacion'
            ]
        ;
        _.forEach(attributes, function(attr, key){
            if(blacklist.indexOf(key) < 0 && blacklist.indexOf(modelname + '.' + key) < 0){
                attrs[key] = attr;
            }
        });
        res.json({
            attrs: attrs
        });
    }
};

