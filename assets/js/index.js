$ = jQuery;
let page = 0;

$( document ).ready(function() {
    
    // setNextStep(page);
    
    function setNextStep (page) {

        $.ajax({
            url         : '/BitrixControl.php',
            type        : 'POST',
            dataType    : 'json',
            data        : {
                b24_source: b24_source,
                page: page
            },
        })
        .done(data => {

            if (!data.end_table) {

                console.log(data.updated_contacts);
                
                $('#log').append( '<p>Обновленные ID: ' + data.updated_contacts + '</p>' );
                
                page++;
                setTimeout(() => {
                    setNextStep(page)
                }, 5000);
            } else {
                $('#log').append('<h2>Алгоритм завершен!</h2>');
            }
        })
        .fail( function (data) { 

            if (!data.end_table) {

                page++;
                setTimeout(() => {
                    setNextStep(page)
                }, 5000);
            }
        });
    }

});