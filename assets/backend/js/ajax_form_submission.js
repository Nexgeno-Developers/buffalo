//Form Submition
function ajaxSubmit(e, form, callBackFunction) {

    if(form.valid()) {
        e.preventDefault();

        var action = form.attr('action');
        var form2 = e.target;
        var data = new FormData(form2);
        
        var submit_button  = form.find("[type='submit']"); 
        var text_of_button = submit_button.text();
        
        $(submit_button).prop('disabled', true);
        $(submit_button).css('cursor', 'no-drop');
        $(submit_button).text('processing...');
        
        $.ajax({
            type: "POST",
            url: action,
            processData: false,
            contentType: false,
            dataType: 'json',
            data: data,
            success: function(response)
            {
                $(submit_button).prop('disabled', false);
                $(submit_button).css('cursor', 'pointer'); 
				$(submit_button).text(text_of_button);
                
                if (response.status) {
                    sound_play2('success');
                    toastr.success(response.notification);
                    if(form.attr('class') === 'ajaxDeleteForm'){
                        $('#alert-modal').modal('toggle')
                    }else{
                        $('#right-modal').modal('hide');
                    }
                    callBackFunction(response);
                }else{
                    sound_play2('danger');
                    toastr.error(response.notification);
                }
            }
        });
    }else {
        sound_play2('danger');
        toastr.error('Please make sure to fill all the necessary fields');
    }
}

function sound_play2(type) {
    if(type == 'success')
    {
        var audio = document.getElementById("success_sound2");
        audio.currentTime = 0;
        audio.play();            
    }
    else
    {
        var audio = document.getElementById("danger_sound2");
        audio.currentTime = 0;
        audio.play();           
    }
}   
    