function mcd(){
    var checkBox = document.getElementById("sauce");
    var tick=checkBox.checked
    var list = document.getElementById("list")

    if(tick===true){
        list.style.display="block";
        list.style.transition="0.8s ease-out";
    }

    else{
        list.style.display="none";
    }
    window.addEventListener('resize', function() {
        if(window.matchMedia('(min-width: 2000px)').matches){
          var loader=true;
          if(loader){
              window.location.reload(true);
              loader=false;
  
          }
            // document.getElementById('list').style.display="none"
        }
   },tick=true);
}