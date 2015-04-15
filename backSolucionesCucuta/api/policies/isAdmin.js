
module.exports = function isAdmin (req, res, next) {
  var session = req.session;

  if(session.authenticated){
    if(session.usuario){
      Usuario.findOne({id: session.usuario.id}).populate('rol').exec(function(err, usuario){
        if(err){
          res.negotiate(err);
        }
        sails
        if(!usuario || !usuario.hasRol({slug: 'administrador'})){
          return res.forbidden('You are not permitted to perform this action.');
        }else{
          next();
        }
      });
    }else{
      return res.redirect('/Login');
    }
  }else{
    return res.redirect('/Login');
  }
}
