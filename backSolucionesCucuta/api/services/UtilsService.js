
module.exports = {

  generateNavPage: function (etiquetas){
    var nav = [];
    while (etiquetas.length){
      var etiqueta = etiquetas.pop();
      if(etiqueta.etiquetas.length){
        var subnavs = [];
        if(etiqueta.etiquetas.length){
          for(var i in etiqueta.etiquetas){
            var subEtiqueta = etiqueta.etiquetas[i];
            if(subEtiqueta && subEtiqueta.nombre && subEtiqueta.slug){
              var sref = 'nav.subnav({ nav: "'+etiqueta.slug+'", subnav: "'+subEtiqueta.slug+'"})',
                sub = {
                  sref:   sref,
                  src:    false,
                  nombre: subEtiqueta.nombre.toLowerCase()
                };
              /*if(subEtiqueta.archivos.length && subEtiqueta.archivos[0].url){
                sub.src = subEtiqueta.archivos[0].url;
              }*/
              if(subEtiqueta.icon){
                sub.icon = subEtiqueta.icon;
              }
              subnavs.push(sub);
            }
          }
        }
        var sref = 'nav({ nav: "'+etiqueta.slug+'"})', sub = {
          li:     true,
          sref:   sref,
          src:    false,
          nombre: etiqueta.nombre.toLowerCase(),
          subnavs:  subnavs
        };
        if(etiqueta.icon){
          sub.icon = etiqueta.icon;
        }
        nav.push(sub);
      }
    }
    return nav;
  },

  sendEmail: function(options) {

    var opts = {"type":"messages","call":"send","message":
    {
      "subject": "YourIn!",
      "from_email": "info@balderdash.co",
      "from_name": "AmazingStartupApp",
      "to":[
        {"email": options.email, "name": options.name}
      ],
      "text": "Dear "+options.name+",\nYou're in the Beta! Click <insert link> to verify your account"
    }
    };

    myEmailSendingLibrary.send(opts);

  }
};
