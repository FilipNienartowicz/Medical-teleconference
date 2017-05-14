function setImageToCanvas() {
  var canvas = document.getElementById('imageToTransform');
  var context = canvas.getContext('2d');
  var imageObj = new Image();
  var nameOfImage = document.getElementById('imageName').value;

  imageObj.src = nameOfImage;

  imageObj.onload = function() {
    canvas.width = imageObj.width;
    canvas.height = imageObj.height;
    context.drawImage(imageObj, 0, 0);
  };
}
