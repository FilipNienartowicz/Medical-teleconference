var imageFile = document.getElementById('imageFile');
imageFile.addEventListener('change', handleImage, false);
var canvas = document.getElementById('imageToTransform');
var context = canvas.getContext('2d');
var scale = 1;

function handleImage(e){
    var reader = new FileReader();
    reader.onload = function(event){
        var img = new Image();
        img.onload = function(){
            context.drawImage(img,0,0);
        }
        img.src = event.target.result;
        document.getElementById("urlWhy").innerHTML = img.src;         
    }
    reader.readAsDataURL(e.target.files[0]);
}
