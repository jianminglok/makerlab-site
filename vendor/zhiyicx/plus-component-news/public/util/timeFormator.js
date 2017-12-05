// 日期格式化函数
// yyyy/MM/dd hh:mm:ss SSS ⇒ "2017/05/16 09:24:20 850"
//"yyyy/M/d h:m:s SSS"⇒ "2017/5/16 9:24:35 723"
export default Date.prototype.format2 = function (format) {
  var map = {
    'M+': this.getMonth() + 1,
    'd+': this.getDate(),
    'h+': this.getHours(),
    'm+': this.getMinutes(),
    's+': this.getSeconds()
  }
  if (/(y+)/i.test(format)) {
    format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(-RegExp.$1.length));
  }
  for (var k in map) {
    if (new RegExp('(' + k + ')').test(format)) {
      var strValue = map[k] + '';
      var len = RegExp.$1.length < strValue.length ? strValue.length : RegExp.$1.length;
      if (strValue.length == 1) {
        strValue = '0' + strValue;
      }
      format = format.replace(RegExp.$1, strValue.substr(-len));
    }
  }
  if (/(S+)/.test(format)) {
    format = format.replace(RegExp.$1, (this.getMilliseconds() + '').substr(0, RegExp.$1.length));
  }
  return format;
}
