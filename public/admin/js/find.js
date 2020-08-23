function BrowseServer(inputId) {
    CKFinder.popup( {
        basePath : '/ckeditor/ckfinder/', //导入CKFinder的路径
        chooseFiles: true, //开启选择文件选型
        onInit: function( finder ) { //绑定选择事件
            finder.on( 'files:choose', function( evt ) {
                var file = evt.data.files.first();
                document.getElementById(inputId).value = file.getUrl();
            } );
            finder.on( 'file:choose:resizedImage', function( evt ) {
                document.getElementById(inputId).value = evt.data.resizedUrl;
            } );
        }
    } );

}