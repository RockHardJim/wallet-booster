
function activateMember(id) {

        var user_no = document.getElementById("hidden_member_no" + id).value;
      
        var dir = "/dboard/activate/"; 
        //var jsonString = JSON.stringify(reg_array);
        var results = 
        $.ajax({
            url:dir,
            method:"POST",
            async:false,
            data: { member_no:  user_no                                           
                },
            success:function(){                    
            } 
        }).responseText;
        msg = results.trim();
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-danger");
        element.classList.add("alert-success");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},3000);
        return false;

};

function deActivateMember(id) {

        var user_no = document.getElementById("hidden_member_no" + id).value;
      
        var dir = "/dboard/deactivate/"; 
        //var jsonString = JSON.stringify(reg_array);
        var results = 
        $.ajax({
            url:dir,
            method:"POST",
            async:false,
            data: { member_no:  user_no                                           
                },
            success:function(){                    
            } 
        }).responseText;
        msg = results.trim();
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-danger");
        element.classList.add("alert-success");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},3000);
        return false;
};

function claimIncentive(id) {

    var claim_id = document.getElementById("hidden_claim_no" + id).value;
  
    //alert(claim_id);
    var dir = "/dboard/claim/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { claim:  claim_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();

    if(msg=='Incentive Claimed'){
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-danger");
        element.classList.add("alert-success");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},1000);
        return false;
    }else{
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-success");
        element.classList.add("alert-danger");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    }
    

};

function setIncentiveToPaid(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
  
    //alert(claim_id);
    var dir = "/dboard/setIncentiveToPaid/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},1000);
    return false;

};

function setIncentiveToPending(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
  
    var dir = "/dboard/setIncentiveToPending/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},1000);
    return false;

};

function confirmPayment(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
  
    //alert(trans_id);
    
    var dir = "/mystatsdir/confirmPayment/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},5000);
    return false;

};

function reversePayment(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
  
    //alert(trans_id);
    
    var dir = "/mystatsdir/reversePayment/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},5000);
    return false;

};

function deletePayment(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
    var member_no = document.getElementById("hidden_member_no" + id).value;
  
    //alert(trans_id);
    
    var dir = "/mystatsdir/deletePayment/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id,
                member_no: member_no                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},2000);
    return false;
};

function unblockFunction(date,member_no){

    /*var trans_id = document.getElementById("hidden_trans_no" + id).value;
    var member_no = document.getElementById("hidden_member_no" + id).value;*/
  
    //alert(date);
    
    var dir = "/mystatsdir/unblockME/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { date:  date,
              member_no: member_no                                          
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},1000);
    //return false;
};


function upgradeFunction(id) {

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
  
    //alert(trans_id);
    
    var dir = "/mystatsdir/upgrade/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id:  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();

    /*element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = 'Upgrade initiated, please pay the relevant person on time';
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},2000);
    //return false;*/

    if(msg=='Previous Upgrade still pending'){
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-success");
        element.classList.add("alert-danger");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},5000);
        return false;
    }else if(msg=='Session expired, please login again'){
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-success");
        element.classList.add("alert-danger");
        error_message.innerHTML = msg;
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location='/auth/logout';},3000);
        return false;
    }else{
        element = document.getElementById("error_message");
        element.classList.add("alert");
        element.classList.remove("alert-danger");
        element.classList.add("alert-success");
        error_message.innerHTML = 'Upgrade processed, please pay the relevant person on time';
        $("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},5000);
        return false;
    }

};

function updateStageForAll() {

    var dir = "/dboard/updateStageForAll/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { 
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},5000);
    return false;
};

function placeAllMembers() {

    var dir = "/dboard/placeAllMembers/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { 
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},5000);
    return false;
};

$(document).ready(function(){
    $("input:radio[name=option]").on("click",function(){
        
        var option_value = $("input[name='option']:checked").val();
        var dir = "/dboard/adminwithdrawals/"; 


        var results =  $.ajax({
                        url:dir,
                        method:"POST",
                        async:false,
                        data: { option : option                                           
                            },
                        success:function(){                    
                        } 
                    }).responseText;

                    msg = results.trim();

    });
});


function countdown(dates,counter){
    
    var countDownDate = new Date(dates).getTime();

// Update the count down every 1 second
    var x = setInterval(function() {

  // Get today's date and time
        var now = new Date().getTime()

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        //document.getElementById("demo").innerHTML = days + "d " + hours + "h "
        //+ minutes + "m " + seconds + "s ";
        document.getElementById("timer"+counter).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s remains";

        //document.getElementById("timer"+counter).innerHTML = 'Not up';

        // If the count down is finished, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer"+counter).innerHTML = "<strong>Payment period is over</strong>";
            element = document.getElementById("Cancel"+counter);
            element.classList.remove("reject");
        }
    }, 1000);
}

function countdown_test(c){
    //var countDownDate = new Date(dates).getTime();

    alert(c);
}
  

function copyText(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

$(window).resize(function () {
    if($(window).width()<'768')
        document.getElementById('navmenu').style.display = "none";
    else
        document.getElementById('navmenu').style.display = "block";
});

function showDiv() {
    if($('#navmenu').is(':visible'))
        document.getElementById('navmenu').style.display = "none";
    else
        document.getElementById('navmenu').style.display = "block";
}

$(function(){ // this will be called when the DOM is ready
    $('#Testimonial').click(function() {

        var dir = "/mystatsdir/addTestimonial/"; 

        var testimony = document.getElementById("TextAreaTestimonial").value;
        var results = 
        $.ajax({
            url:dir,
            method:"POST",
            async:false,
            data: { tpost : testimony
                  },
            success:function(){                    
            } 
        }).responseText;
        msg = results.trim();
        element = document.getElementById("error_message_testimonial");
        element.classList.add("alert");
        element.classList.remove("alert-danger");
        element.classList.add("alert-success");
        error_message_testimonial.innerHTML = msg;
        //$("html, body").animate({ scrollTop: 0 }, "slow");
        setTimeout(function() { window.location=window.location;},5000);
        return false;

    });
});

function approveTestimonial(id){

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
    //alert(trans_id);
      
    var dir = "/mystatsdir/approveTestimonial/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id :  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},3000);
    return false;


}

function declineTestimonial(id){

    var trans_id = document.getElementById("hidden_trans_no" + id).value;
    //alert(trans_id);
      
    var dir = "/mystatsdir/declineTestimonial/"; 
    //var jsonString = JSON.stringify(reg_array);
    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { trans_id :  trans_id                                           
            },
        success:function(){                    
        } 
    }).responseText;
    msg = results.trim();
    element = document.getElementById("error_message");
    element.classList.add("alert");
    element.classList.remove("alert-danger");
    element.classList.add("alert-success");
    error_message.innerHTML = msg;
    $("html, body").animate({ scrollTop: 0 }, "slow");
    setTimeout(function() { window.location=window.location;},3000);
    return false;


}