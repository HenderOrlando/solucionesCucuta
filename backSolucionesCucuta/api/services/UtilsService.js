
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
              var sref = 'nav({ nav: "'+etiqueta.slug+'"})';
              sref = 'nav.subnav({ nav: "'+etiqueta.slug+'", subnav: "'+subEtiqueta.slug+'"})';
              subnavs.push({
                sref:   sref,
                src:    false,
                nombre: subEtiqueta.nombre.toLowerCase()
              });
            }
          }
        }
        var sref = 'nav({ nav: "'+etiqueta.slug+'"})';
        nav.push({
          li:     true,
          sref:   sref,
          src:    false,
          nombre: etiqueta.nombre.toLowerCase(),
          subnavs:  subnavs
        });
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
