<html>
<body>
<pre class="article-example"><p><input type="file" id="file1" multiple=""></p>
<div id="list1"></div>
<script type="text/javascript">
(function() {
var html = [];
function fileSelect1(e) {
    var files = this.files;
    for(var i = 0, len = files.length; i < len; i++) {
        var f = files[i];
        html.push(
            '<p>',
                f.name + '(' + (f.type || "n/a") + ')' + ' - ' + f.size + 'bytes',
            '</p>'
        );
    }
    document.getElementById('list1').innerHTML = html.join('');
}
if(isSupportFileApi()) {
    document.getElementById('file1').onchange = fileSelect1;
}
})();
</script>
</pre>
</body>
</html>