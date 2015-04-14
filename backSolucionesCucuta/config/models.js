/**
 * Default model configuration
 * (sails.config.models)
 *
 * Unless you override them, the following properties will be included
 * in each of your models.
 *
 * For more info on Sails models, see:
 * http://sailsjs.org/#/documentation/concepts/ORM
 */

module.exports.models = {
  /***************************************************************************
  *                                                                          *
  * Your app's default connection. i.e. the name of one of your app's        *
  * connections (see `config/connections.js`)                                *
  *                                                                          *
  ***************************************************************************/
  // connection: 'localDiskDb',
  connection: 'mysqlServer',

  /***************************************************************************
  *                                                                          *
  * How and whether Sails will attempt to automatically rebuild the          *
  * tables/collections/etc. in your schema.                                  *
  *                                                                          *
  * See http://sailsjs.org/#/documentation/concepts/ORM/model-settings.html  *
  *                                                                          *
  ***************************************************************************/
  //migrate: 'alter',
  migrate: 'safe',

  createUUID: function(){
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
      s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
  },

  capitalizeSlug: function (input) {
    input = input.toString().replace(/^./, function (match) {
      return match.toUpperCase();
    });
    return input.replace(/\s+/g,'');
  },

  cleanInputs: function(inputs){
    inputs.file =
    inputs._csrf =
    inputs.csrf =
        undefined;

  }
};
