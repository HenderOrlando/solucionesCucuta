
module.exports = function isAuthenticated (req, res, next) {
  var session = req.session;
  if(session.authenticated && session.usuario){
    return next();
  }
  return res.redirect('/Login');
}
