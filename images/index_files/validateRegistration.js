var firstname_count  = 1;
var middlename_count = 1;
var lastname_count   = 1;
var idnumber_count = 1;
var passportnumber_count  = 1;
var mobile_count = 1;
var sponsor_count = 1;
var email_count   = 1;
var username_count = 1;
var password_count = 1;
var confirmpassword_count = 1;
var accholder_count = 1;
var accnumber_count = 1;
var bank_count = 1;
var reg_array = [];
//reg_array['firstname'] = 'Amen';

/*window.onload = function() {
  //var input = document.getElementById("Sponsor").focus();sponsor
  var field = document.getElementById("Sponsor").value;
        var msg = "";

        var dir = "/sign/checkActiveUsernameExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Whitespace and Special characters not allowed";
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_sponsor.innerHTML = msg;

        //alert(msg);
        
        if (msg==='Sponsor id not found'){
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(msg==="<div class='l'>Sponsor is available</div>"){
            sponsor_count = 0;
            reg_array['sponsor'] = field.trim();
            sponsor_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else{
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }
}*/

$(function(){ // this will be called when the DOM is ready
    $('#Firstname').keyup(function()  {
        var field = document.getElementById("Firstname").value;
        var msg = "";

        if(field===""){
            msg = "This cannot be black";
        }else if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Invalid name";
        }

        error_name.innerHTML = msg;

        if (msg!=""){
            firstname_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            firstname_count = 1;
        }else{
            firstname_tick.innerHTML="<img src='/images/checkmark.png'/>";
            firstname_count = 0;
            reg_array['firstname'] = field.trim().trim();
        }
       
        error_name.innerHTML = msg;
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Firstname').click(function()  {
        var field = document.getElementById("Firstname").value;
        var msg = "";

        if(field===""){
            msg = "This cannot be black";
        }else if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Invalid name";
        }

        error_name.innerHTML = msg;

        if (msg!=""){
            firstname_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            firstname_count = 1;
        }else{
            firstname_tick.innerHTML="<img src='/images/checkmark.png'/>";
            firstname_count = 0;
            reg_array['firstname'] = field.trim();
        }
       
        error_name.innerHTML = msg;
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Middlename').keyup(function() {
        var field = document.getElementById("Middlename").value;
        var msg = "";

        if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Numbers & special characters not accepted";
        }

        error_middlename.innerHTML = msg;
        if (msg!=""){
            middlename_count = 1;
            middlename_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            middlename_count = 0;
            reg_array['middlename'] = field.trim();
            middlename_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Middlename').click(function() {
        var field = document.getElementById("Middlename").value;
        var msg = "";

        if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Numbers & special characters not accepted";
        }

        error_middlename.innerHTML = msg;
        if (msg!=""){
            middlename_count = 1;
            middlename_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            middlename_count = 0;
            reg_array['middlename'] = field.trim();
            middlename_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Lastname').keyup(function() {
        var field = document.getElementById("Lastname").value;
        var msg = "";

        if(field===""){ 
            msg = "This cannot be black";
        }else if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Invalid surname";
        }

        error_lastname.innerHTML = msg;
        if (msg!=""){
            lastname_count = 1;
            lastname_tick.innerHTML="<img class='tick' src='/images/checkmark_2.png'/>";
        }else{
            lastname_count = 0;
            reg_array['lastname'] = field.trim();
            lastname_tick.innerHTML="<img class='tick' src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Lastname').click(function() {
        var field = document.getElementById("Lastname").value;
        var msg = "";

        if(field===""){ 
            msg = "This cannot be black";
        }else if(/^[a-zA-Z]*$/.test(field.trim()) == false){
            msg = "Invalid surname";
        }

        error_lastname.innerHTML = msg;
        if (msg!=""){
            lastname_count = 1;
            lastname_tick.innerHTML="<img class='tick' src='/images/checkmark_2.png'/>";
        }else{
            lastname_count = 0;
            reg_array['lastname'] = field.trim();
            lastname_tick.innerHTML="<img class='tick' src='/images/checkmark.png'/>";
        }
       
    });
});

function populateCountryCode(){

    var field = document.getElementById("Country").value;

    var dir = "/sign/populate/";

    $.ajax({
        url:dir,
        method:"POST",
        data:{iso:field},
        success:function(data){
            $('#MobileNumber').val(data);
        } 
    })

    if(field=='ZA'){
        $('.idNumber').show();
        $('.passportNumber').hide();
    }
    else{
        $('.idNumber').hide();
        $('.passportNumber').show();
    }
}

$(function(){ // this will be called when the DOM is ready
    $('#PassportNumber').keyup(function() {
        var field = document.getElementById("PassportNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var msg = "";

        var dir = "/sign/checkPassportExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Invalid Passport number";
        }else{
            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim();          
        }

        $('#PassportNumber').val(field);

        error_passport.innerHTML = msg;
        if (msg!=""){
            passportnumber_count  = 1;
            passportnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            passportnumber_count  = 0;
            reg_array['passport'] = field.trim();
            passportnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#PassportNumber').click(function() {
        var field = document.getElementById("PassportNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var msg = "";

        var dir = "/sign/checkPassportExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Invalid Passport number";
        }else{
            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim();          
        }

        $('#PassportNumber').val(field);

        error_passport.innerHTML = msg;
        if (msg!=""){
            passportnumber_count  = 1;
            passportnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            passportnumber_count  = 0;
            reg_array['passport'] = field.trim();
            passportnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#IdNumber').keyup(function() {
        var field = document.getElementById("IdNumber").value;
        var gender = document.getElementById("Gender").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var ver_gender = Number(field.substring(6, 10));

        var msg = "";
        var result;

        var dir = "/sign/checkIdExists/";

        if(field===""){
            msg = "Your identity number is required";
        }else if(/^[0-9\-]*$/.test(field.trim()) == false){
            msg = "Invalid South African ID number";
        }else if(field.length!=13){
            msg = "Invalid South African ID number"; 
        }else if((gender=='Male' & ver_gender<5000) || (gender=='Female' & ver_gender>4999)){
            msg = "SA ID Number and Gender do not correspond"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                global:false,
                dataType: "text",
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            result = results; 
             
            if (result.trim()=="ID number already signed up")
                msg = result;
             
        }

        $('#IdNumber').val(field);

        if (msg===""){
            error_idnumber.innerHTML = msg;
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            idnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else {
            error_idnumber.innerHTML = msg;
            idnumber_count = 1;
            idnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }       
    });

    $('#Gender').change(function() {
        var field = document.getElementById("IdNumber").value;
        var gender = document.getElementById("Gender").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var ver_gender = Number(field.substring(6, 10));

        var msg = "";
        var result="";

        var dir = "/sign/checkIdExists/";

        if(field===""){
            msg = " ";
        }else if(/^[0-9\-]*$/.test(field.trim()) == false){
            msg = "Invalid South African ID number";
        }else if(field.length!=13){
            msg = "Invalid South African ID number"; 
        }else if((gender=='Male' & ver_gender<5000) || (gender=='Female' & ver_gender>4999)){
            msg = "SA ID Number and Gender do not correspond"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            result = results; 
             
            if (result.trim()==="ID number already signed up")
                msg = result;
        }

        $('#IdNumber').val(field);

        if (msg===""){
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "<img src='/images/checkmark.png'/>";
        }else if (msg===" "){
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "";
        }else{
            idnumber_count = 1;
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "<img src='/images/checkmark_2.png'/>";
        }   
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#IdNumber').click(function() {
        var field = document.getElementById("IdNumber").value;
        var gender = document.getElementById("Gender").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var ver_gender = Number(field.substring(6, 10));

        var msg = "";
        var result;

        var dir = "/sign/checkIdExists/";

        if(field===""){
            msg = "Your identity number is required";
        }else if(/^[0-9\-]*$/.test(field.trim()) == false){
            msg = "Invalid South African ID number";
        }else if(field.length!=13){
            msg = "Invalid South African ID number"; 
        }else if((gender=='Male' & ver_gender<5000) || (gender=='Female' & ver_gender>4999)){
            msg = "SA ID Number and Gender do not correspond"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                global:false,
                dataType: "text",
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            result = results; 
             
            if (result.trim()=="ID number already signed up")
                msg = result;
             
        }

        $('#IdNumber').val(field);

        if (msg===""){
            error_idnumber.innerHTML = msg;
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            idnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else {
            error_idnumber.innerHTML = msg;
            idnumber_count = 1;
            idnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }       
    });

    $('#Gender').change(function() {
        var field = document.getElementById("IdNumber").value;
        var gender = document.getElementById("Gender").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var ver_gender = Number(field.substring(6, 10));

        var msg = "";
        var result="";

        var dir = "/sign/checkIdExists/";

        if(field===""){
            msg = " ";
        }else if(/^[0-9\-]*$/.test(field.trim()) == false){
            msg = "Invalid South African ID number";
        }else if(field.length!=13){
            msg = "Invalid South African ID number"; 
        }else if((gender=='Male' & ver_gender<5000) || (gender=='Female' & ver_gender>4999)){
            msg = "SA ID Number and Gender do not correspond"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            result = results; 
             
            if (result.trim()==="ID number already signed up")
                msg = result;
        }

        $('#IdNumber').val(field);

        if (msg===""){
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "<img src='/images/checkmark.png'/>";
        }else if (msg===" "){
            idnumber_count = 0;
            reg_array['idnumber'] = field.trim();
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "";
        }else{
            idnumber_count = 1;
            error_idnumber.innerHTML = msg;
            idnumber_tick.innerHTML = "<img src='/images/checkmark_2.png'/>";
        }   
    });
});


$(function(){ // this will be called when the DOM is ready
    $('#MobileNumber').keyup(function() {
        var field = document.getElementById("MobileNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');
        var msg = "";
        
        if(field===""){
            msg = "This is compulsory";
        }else if(/^[0-9 \-+]*$/.test(field.trim()) == false){
            msg = "Invalid Mobile number";
        }else if(field.length<10){
            msg = "Invalid cellphone"; 
        }

        error_mobilenumber.innerHTML = msg;

        if (msg!=""){
            mobile_count = 1;
            mobile_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            mobile_count = 0;
            reg_array['mobile'] = field.trim();
            mobile_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#MobileNumber').click(function() {
        var field = document.getElementById("MobileNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');
        var msg = "";
        
        if(field===""){
            msg = "This is compulsory";
        }else if(/^[0-9 \-+]*$/.test(field.trim()) == false){
            msg = "Invalid Mobile number";
        }else if(field.length<10){
            msg = "Invalid contacts"; 
        }

        error_mobilenumber.innerHTML = msg;

        if (msg!=""){
            mobile_count = 1;
            mobile_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            mobile_count = 0;
            reg_array['mobile'] = field.trim();
            mobile_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});

$(function(){ //this will be called when the DOM is ready
    $('#Sponsor').keyup(function() {
        var field = document.getElementById("Sponsor").value;
        var msg = "";

        var dir = "/sign/checkActiveUsernameExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Whitespace and Special characters not allowed";
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_sponsor.innerHTML = msg;

        //alert(msg);
        
        if (msg==='Sponsor id not found'){
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(msg==="<div class='l'>Sponsor is available</div>"){
            sponsor_count = 0;
            reg_array['sponsor'] = field.trim();
            sponsor_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else{
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }

    });
});

$(function(){ //this will be called when the DOM is ready
    $('#Sponsor').click(function() {
        var field = document.getElementById("Sponsor").value;
        var msg = "";

        var dir = "/sign/checkActiveUsernameExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Whitespace and Special characters not allowed";
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_sponsor.innerHTML = msg;

        //alert(msg);
        
        if (msg==='Sponsor id not found'){
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(msg==="<div class='l'>Sponsor is available</div>"){
            sponsor_count = 0;
            reg_array['sponsor'] = field.trim();
            sponsor_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else{
            sponsor_count = 1;
            sponsor_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }

    });
});

$(function(){ //this will be called when the DOM is ready
    $('#EmailAddress').keyup(function() {
        var field = document.getElementById("EmailAddress").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var msg = "";
        var dir = "/sign/checkEmailExists/";
        
        if(field==""){
            msg = "Email is required";
        }else if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,3})+$/.test(field.trim()) == false){
            msg = "Invalid Email address";
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_email.innerHTML = msg;

        if (msg!=""){
            email_count = 1;
            email_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            email_count = 0;
            reg_array['email'] = field.trim();
            email_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});

$(function(){ //this will be called when the DOM is ready
    $('#EmailAddress').click(function() {
        var field = document.getElementById("EmailAddress").value;
        field = field.trim();
        field = field.replace(/\s/g, '');

        var msg = "";
        var dir = "/sign/checkEmailExists/";
        
        if(field==""){
            msg = "Email is required";
        }else if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,3})+$/.test(field.trim()) == false){
            msg = "Invalid Email address";
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_email.innerHTML = msg;

        if (msg!=""){
            email_count = 1;
            email_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            email_count = 0;
            reg_array['email'] = field.trim();
            email_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});

$(function(){ //this will be called when the DOM is ready
    $('#Username').keyup(function() {
        var field = document.getElementById("Username").value;
        var msg = "";

        var dir = "/sign/checkUsernameExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Whitespace and Special characters not allowed";
        }else if(field.length<5){
            msg = "Too short"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_username.innerHTML = msg;

        //alert(msg);
        
        if (msg==='Sorry this username is not available'){
            username_count = 1;
            username_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(msg==="<div class='l'>This username is available</div>"){
            username_count = 0;
            reg_array['username'] = field.trim();
            username_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else{
            username_count = 1;
            username_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }

    });
});

$(function(){ //this will be called when the DOM is ready
    $('#Username').click(function() {
        var field = document.getElementById("Username").value;
        var msg = "";

        var dir = "/sign/checkUsernameExists/";

        if(field===""){
            msg = "This field is required";
        }else if(/^[a-zA-Z0-9]*$/.test(field.trim()) == false){
            msg = "Whitespace and Special characters not allowed";
        }else if(field.length<5){
            msg = "Too short"; 
        }else{

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data:{id:field},
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim(); 
        }

        error_username.innerHTML = msg;

        //alert(msg);
        
        if (msg==='Sorry this username is not available'){
            username_count = 1;
            username_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(msg==="<div class='l'>This username is available</div>"){
            username_count = 0;
            reg_array['username'] = field.trim();
            username_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else{
            username_count = 1;
            username_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }

    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Password').keyup(function() {

        var field = document.getElementById("Password").value;
        var msg = "";

        field = field.trim();

        if(field===""){
            msg = "Atleast 4 character required";
        }else if(field.length<4){
            msg = "Atleast 4 character required"; 
        }

        if(msg===""){
            password_count = 0;
            reg_array['password'] = field.trim();
            password_tick.innerHTML="<img src='/images/checkmark.png'/>";
            error_password_2.innerHTML = msg;
        }else{
            password_count = 1;
            password_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            error_password_2.innerHTML = msg;
        }

        

        /*
        var textValue = $(this).val();
        var result = zxcvbn(textValue);
        $('.error_password').html("cracktime : " + result.score);
        //error_username.innerHTML = 'Test';
        var field="";

        if(result.score===0){
            var element = document.getElementById("error_password");
            element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("very_weak");
            $('.error_password').html("<hr />");

            element = document.getElementById("error_comment");
            /*element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("very_weak");*/
            //$('.error_comment').html("&nbsp; Very Weak");
            /*error_password_2.innerHTML = "Very Weak Password";
            password_count = 1;

            password_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(result.score===1){
            var element = document.getElementById("error_password");
            element.classList.remove("very_weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("weak");
            $('.error_password').html("<hr />");

            element = document.getElementById("error_comment");
            /*element.classList.remove("very_weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("weak");*/
            //$('.error_comment').html("&nbsp; Weak");
            /*error_password_2.innerHTML = "Weak Password";
            password_count = 1;

            password_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(result.score===2){
            var element = document.getElementById("error_password");
            element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("good");
            $('.error_password').html("<hr />");

            element = document.getElementById("error_comment");
            /*element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("strong");
            element.classList.remove("very_strong");
            element.classList.add("good");*/
            //$('.error_comment').html("&nbsp; Good but not enough");
           /* error_password_2.innerHTML = "<h style='color:orange;'>Fair Password</h>";
            password_count = 1;

            password_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else if(result.score===3){
            var element = document.getElementById("error_password");
            element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("very_strong");
            element.classList.add("strong");
            $('.error_password').html("<hr />");

            element = document.getElementById("error_comment");
            /*element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("very_strong");
            element.classList.add("strong");*/
            //$('.error_comment').html("&nbsp; Partially Strong");
            /*error_password_2.innerHTML = "<h style='color:green;'>Strong Password</h>";
            password_count = 0;
            reg_array['password'] = field.trim();

            password_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }else if(result.score===4){
            var element = document.getElementById("error_password");
            element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.add("very_strong");
            $('.error_password').html("<hr />");

            element = document.getElementById("error_comment");
            /*element.classList.remove("very_weak");
            element.classList.remove("weak");
            element.classList.remove("good");
            element.classList.remove("strong");
            element.classList.add("very_strong");*/
            //$('.error_comment').html("&nbsp; Very Strong");
            /*error_password_2.innerHTML = "<h style='color:green;'>Very Strong Password</h>";
            password_count = 0;
            reg_array['password'] = field.trim();

            password_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }*/
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Password').click(function() {
        var field = document.getElementById("Password").value;
        var msg = "";

        field = field.trim();

        if(field===""){
            msg = "Atleast 4 character required";
        }else if(field.length<4){
            msg = "Atleast 4 character required"; 
        }

        if(msg===""){
            password_count = 0;
            reg_array['password'] = field.trim();
            password_tick.innerHTML="<img src='/images/checkmark.png'/>";
            error_password_2.innerHTML = msg;
        }else{
            password_count = 1;
            password_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            error_password_2.innerHTML = msg;
        }
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#ConfirmPassword').keyup(function() {
        var field = document.getElementById("ConfirmPassword").value;
        var pass  = document.getElementById("Password").value;
        var msg = "";

        if(field!=pass){
            msg = "Passwords not identical";
        }

        error_confirmpassword.innerHTML = msg;

        if (msg!=""){
            confirmpassword_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            confirmpassword_count = 1;
        }else{
            confirmpassword_count = 0;
            reg_array['password'] = field.trim();
            confirmpassword_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#ConfirmPassword').click(function() {
        var field = document.getElementById("ConfirmPassword").value;
        var pass  = document.getElementById("Password").value;
        var msg = "";

        if(field!=pass){
            msg = "Passwords not identical";
        }

        error_confirmpassword.innerHTML = msg;

        if (msg!=""){
            confirmpassword_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            confirmpassword_count = 0;
            reg_array['password'] = field.trim();
            confirmpassword_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }
       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#AccHolder').keyup(function()  {
        var field = document.getElementById("AccHolder").value;
        var msg = "";

        if(field===""){
            msg = "This is compulsory";
        }

        error_accholder.innerHTML = msg;

        if (msg!=""){
            accholder_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            accholder_count = 1;
        }else{
            accholder_tick.innerHTML="<img src='/images/checkmark.png'/>";
            accholder_count = 0;
            reg_array['accholder'] = field.trim();
        }
       
        error_accholder.innerHTML = msg;
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#AccHolder').click(function()  {
        var field = document.getElementById("AccHolder").value;
        var msg = "";

        if(field===""){
            msg = "This is compulsory";
        }

        error_accholder.innerHTML = msg;

        if (msg!=""){
            accholder_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            accholder_count = 1;
        }else{
            accholder_tick.innerHTML="<img src='/images/checkmark.png'/>";
            accholder_count = 0;
            reg_array['accholder'] = field.trim();
        }
       
        error_accholder.innerHTML = msg;
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Bank').keyup(function()  {
        var field = document.getElementById("Bank").value;
        var msg = "";

        if(field===""){
            msg = "This is compulsory";
        }

        error_bank.innerHTML = msg;

        if (msg!=""){
            bank_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            bank_count = 1;
        }else{
            bank_tick.innerHTML="<img src='/images/checkmark.png'/>";
            bank_count = 0;
            reg_array['bank'] = field.trim();
        }
       
        error_bank.innerHTML = msg;
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Bank').click(function()  {
        var field = document.getElementById("Bank").value;
        var msg = "";

        if(field===""){
            msg = "This is compulsory";
        }

        error_bank.innerHTML = msg;

        if (msg!=""){
            bank_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
            bank_count = 1;
        }else{
            bank_tick.innerHTML="<img src='/images/checkmark.png'/>";
            bank_count = 0;
            reg_array['bank'] = field.trim();
        }
       
        error_bank.innerHTML = msg;
    });
});



$(function(){ // this will be called when the DOM is ready
    $('#AccNumber').keyup(function() {
        var field = document.getElementById("AccNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');
        var msg = "";
        
        if(field===""){
            msg = "This is compulsory";
        }else if(/^[0-9 \-+]*$/.test(field.trim()) == false){
            msg = "Incorrect account number";
        }else if(field.length<5){
            msg = "Incorrect account number"; 
        }

        error_accnumber.innerHTML = msg;

        if (msg!=""){
            accnumber_count = 1;
            accnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            accnumber_count = 0;
            reg_array['accnumber'] = field.trim();
            accnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#AccNumber').click(function() {
        var field = document.getElementById("AccNumber").value;
        field = field.trim();
        field = field.replace(/\s/g, '');
        var msg = "";
        
        if(field===""){
            msg = "This is compulsory";
        }else if(/^[0-9 \-+]*$/.test(field.trim()) == false){
            msg = "Incorrect account number";
        }else if(field.length<5){
            msg = "Incorrect account number"; 
        }

        error_accnumber.innerHTML = msg;

        if (msg!=""){
            accnumber_count = 1;
            accnumber_tick.innerHTML="<img src='/images/checkmark_2.png'/>";
        }else{
            accnumber_count = 0;
            reg_array['accnumber'] = field.trim();
            accnumber_tick.innerHTML="<img src='/images/checkmark.png'/>";
        }       
    });
});



$(function(){ // this will be called when the DOM is ready
    $('#Register').click(function() {

        $("#Firstname").keyup();
        $("#Lastname").keyup();
        $("#EmailAddress").keyup();
        $("#Username").keyup();
        $("#Password").keyup();
        $("#ConfirmPassword").keyup();
        $("#MobileNumber").keyup();
        $("#AccHolder").keyup();
        $("#AccNumber").keyup();
        $("#Bank").keyup();

        var mycountry =  document.getElementById("Country").value;
        var counter = firstname_count 
                    + lastname_count   
                    + mobile_count
                    + sponsor_count
                    + email_count
                    + username_count
                    + password_count
                    + confirmpassword_count
                    + accholder_count
                    + bank_count
                    + accnumber_count;

        if(counter==0){        

            var dir = "/sign/signUp/"; 
            //var jsonString = JSON.stringify(reg_array);

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data: { firstname:  reg_array['firstname'],
                        lastname:   reg_array['lastname'],
                        email:      reg_array['email'],
                        country:    mycountry,
                        mobile:     reg_array['mobile'],
                        sponsor:    reg_array['sponsor'], 
                        username:   reg_array['username'],
                        password:   reg_array['password'],
                        accholder:  reg_array['accholder'],
                        bank:       reg_array['bank'],
                        accnumber:  reg_array['accnumber']                    
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
            setTimeout(function() { window.location='/login/';},1500);
            return false;

        }else{
            //alert('Hello World'); 
            //$('#firstname').value('Johnny Bravo');
            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.remove("alert-success");
            element.classList.add("alert-danger");
            error_message.innerHTML = 'Please fix all the errors below';
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        }
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#Login').click(function() {

        
        var username =  document.getElementById("Lusername").value;
        var password =  document.getElementById("Lpassword").value;
        
        var dir = "/sign/login/"; 
        //var jsonString = JSON.stringify(reg_array);
        var results = 
        $.ajax({
            url:dir,
            method:"POST",
            async:false,
            data: { uname:  username,
                    pword:  password                   
                  },
            success:function(){                    
            } 
        }).responseText;
        msg = results.trim();

        if(msg=='unsuccessful'){
            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.add("alert-danger");
            error_message.innerHTML = msg;
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        }else{
            window.location.href = "/mystatsdir/index/";
        }
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#ShowPassword').click(function() {
        var x = document.getElementById("Password");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }

        var x = document.getElementById("ConfirmPassword");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#UpdateAccountDetails').click(function() {

        $("#Firstname").keyup();
        $("#Lastname").keyup();
        $("#Password").keyup();
        $("#ConfirmPassword").keyup();
        $("#MobileNumber").keyup();

        var counter = firstname_count 
                    + lastname_count   
                    + mobile_count
                    + password_count
                    + confirmpassword_count;

        if(counter==0){        

            var dir = "/mystatsdir/updateMemberDetails/"; 
            //var jsonString = JSON.stringify(reg_array);

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data: { firstname:  reg_array['firstname'],
                        lastname:   reg_array['lastname'],
                        mobile:     reg_array['mobile'],
                        password:   reg_array['password']                                           
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
            return false;

        }else{
            //alert('Hello World'); 
            //$('#firstname').value('Johnny Bravo');
            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.remove("alert-success");
            element.classList.add("alert-danger");
            error_message.innerHTML = 'Oops errors encountered';
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        }
    });
});

$(function(){ // this will be called when the DOM is ready
    $('#UpdateBankingDetails').click(function() {

        $("#AccHolder").keyup();
        $("#AccNumber").keyup();
        $("#Bank").keyup();

        var counter = accholder_count 
                    + bank_count   
                    + accnumber_count;

        if(counter==0){        

            var dir = "/mystatsdir/updateBankingDetails/"; 
            //var jsonString = JSON.stringify(reg_array);

            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data: { accholder:  reg_array['accholder'],
                        bank:       reg_array['bank'],
                        accnumber:  reg_array['accnumber']                                          
                    },
                success:function(){                    
                } 
            }).responseText;

            msg = results.trim();

            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.remove("alert-danger");
            element.classList.add("alert-success");
            error_message.innerHTML = 'Financial details updated';
            /*$("html, body").animate({ scrollTop: 0 }, "slow");
            return false;*/

        }else{
            //alert('Hello World'); 
            //$('#firstname').value('Johnny Bravo');
            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.remove("alert-success");
            element.classList.add("alert-danger");
            error_message.innerHTML = 'Changes could not be saved';
            /*$("html, body").animate({ scrollTop: 0 }, "slow");
            return false;*/
        }
    });
});


$(function(){ // this will be called when the DOM is ready
    $('#SendPassword').click(function() {

        var username =  document.getElementById("Fusername").value;

        if(username===""){
            msg = "Your username is required";
        }
        
        if(username!==""){
            var dir = "/sign/sendPassword/"; 
            //var jsonString = JSON.stringify(reg_array);
            var results = 
            $.ajax({
                url:dir,
                method:"POST",
                async:false,
                data: { uname:  username                  
                      },
                success:function(){                    
                } 
            }).responseText;
            msg = results.trim();

            if(msg=='unsuccessful'){
                element = document.getElementById("error_message");
                element.classList.add("alert");
                element.classList.remove("alert-success");
                element.classList.add("alert-danger");
                error_message.innerHTML = 'Error sending an email, please check your username';
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            }else{
                element = document.getElementById("error_message");
                element.classList.add("alert");
                element.classList.remove("alert-danger");
                element.classList.add("alert-success");
                error_message.innerHTML = 'Password Reminder sent via sms and email';
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            }
        }else{
            element = document.getElementById("error_message");
            element.classList.add("alert");
            element.classList.remove("alert-success");
            element.classList.add("alert-danger");
            error_message.innerHTML = msg;
        }

    });
});

$(function () {
    $(document).scroll(function () {
      var $nav = $(".navbar-fixed-top");
      $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
    });
  });