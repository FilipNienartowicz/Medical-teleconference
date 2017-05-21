function draw(scale, translatePos, url){
    var canvas = document.getElementById("imageToTransform");
    var context = canvas.getContext("2d");
    var img = new Image();
    var url = canvas.toDataURL();

    img.onload = function() {
      context.clearRect(0, 0, canvas.width, canvas.height);
      context.save();
      context.translate(translatePos.x, translatePos.y);
      context.scale(scale, scale);
      context.drawImage(img,0,0);
      context.restore();
    }
    img.src = $("#urlWhy").text();

}

window.onload = function(){
    var canvas = document.getElementById("imageToTransform");
    var url = canvas.toDataURL();

    var translatePos = {
        x: 0,
        y: 0
    };

    var scale = 1.0;
    var scaleMultiplier = 0.8;
    var startDragOffset = {};
    var mouseDown = false;

    // add button event listeners
    document.getElementById("plus").addEventListener("click", function(){
        scale /= scaleMultiplier;
        draw(scale, translatePos, url);
    }, false);

    document.getElementById("minus").addEventListener("click", function(){
        scale *= scaleMultiplier;
        draw(scale, translatePos, url);
    }, false);

    // add event listeners to handle screen drag
    canvas.addEventListener("mousedown", function(evt){
        mouseDown = true;
        startDragOffset.x = evt.clientX - translatePos.x;
        startDragOffset.y = evt.clientY - translatePos.y;
    });

    canvas.addEventListener("mouseup", function(evt){
        mouseDown = false;
    });

    canvas.addEventListener("mouseover", function(evt){
        mouseDown = false;
    });

    canvas.addEventListener("mouseout", function(evt){
        mouseDown = false;
    });

    canvas.addEventListener("mousemove", function(evt){
        if (mouseDown) {
            translatePos.x = evt.clientX - startDragOffset.x;
            translatePos.y = evt.clientY - startDragOffset.y;
            draw(scale, translatePos, url);
        }
    });

    draw(scale, translatePos, url);
};
