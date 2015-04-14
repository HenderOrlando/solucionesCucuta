
module.exports = function isAdmin (req, res, next) {
  var session = req.session;

  if(session.authenticated){
    if(session.usuario){
      Usuario.find({id: session.usuario.id}).populate('rol',{slug: 'administrador'}).exec(function(err, usuario){
        //sails.log.debug(usuario)
        sails.log.info(' => Error <=')
        sails.log.verbose(err)
        if(err){
          res.negotiate(err);
        }
        sails.log.info(' => Usuario <=')
        //sails.log.debug(usuario)
        sails.log.debug('Hender')
        sails.log.debug(usuario.rol)
        sails.log.debug(usuario.rol.slug)
        if(!usuario || (usuario && usuario.rol && usuario.rol.slug && usuario.rol.slug != 'administrador')){
          return res.forbidden('You are not permitted to perform this action.');
        }
      });
    }else{
      return res.redirect('/Login');
    }
  }else{
    return res.redirect('/Login');
  }
}
