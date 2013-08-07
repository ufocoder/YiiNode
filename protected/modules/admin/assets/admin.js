$(document).ready(function(){

    $('a[data-confirm-content]').click(function(){
        return false;
        var confirmUrl = $(this).attr('href'),
            confirmTitle = $(this).attr('data-confirm-title'),
            confirmContent = $(this).attr('data-confirm-content'),
            html = '<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">'
                +'    <div class="modal-header">'
                +'        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>'
                +'        <h3 id="dataConfirmLabel">' + confirmTitle + '</h3>'
                +'    </div>'
                +'    <div class="modal-body">' + confirmContent + '</div>'
                +'    <div class="modal-footer">'
                +'        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>'
                +'        <a class="btn btn-primary" href="' + confirmUrl + '">OK</a>'
                +'    </div>'
                + '</div>';

        if ($('#dataConfirmModal').length)
            $('#dataConfirmModal').remove();

        $('body').append(html);

        $('#dataConfirmModal').modal({show:true});
        return false;
    });

});