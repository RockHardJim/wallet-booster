$(function(){
    $('#org').orgChart({container: $("#main"), stack: true, depth: 7});
})

function displayLevel(r){
    var s = r;
    var dir = "/mystatsdir/changeGlobalLevel/"; 
            //var jsonString = JSON.stringify(reg_array);

    var results = 
    $.ajax({
        url:dir,
        method:"POST",
        async:false,
        data: { globalLevel:  s                                        
            },
        success:function(){                    
        } 
    })
    
    setTimeout(function() { window.location=window.location;},0);
    return false;
    $('#org').orgChart({container: $("#main"), stack: true, depth: r});
    
}