var Marker = function () {
  this.Sprite = new Image();
  this.Sprite.src = "http://www.iconsdb.com/icons/preview/green/map-marker-2-xxl.png"
  this.Width = 12;
  this.Height = 20;
  this.XPos = 0;
  this.YPos = 0;
}

function draw(mix, scale, translatePos, rotate, Markers, url){
    var canvas = document.getElementById("imageToTransform");
    var context = canvas.getContext("2d");
    var img = new Image();
    var url = canvas.toDataURL();

    img.onload = function() {
      context.clearRect(0, 0, canvas.width, canvas.height);
      context.save();
      context.translate(translatePos.x, translatePos.y);
      context.rotate(rotate*Math.PI/180);
      context.scale(scale, scale);
      context.drawImage(img,0,0);
      sharpen(mix);
      context.restore();

      /*for (var i = 0; i < Markers.length; i++) {
        var tempMarker = Markers[i];
        context.drawImage(tempMarker.Sprite, tempMarker.XPos, tempMarker.YPos, tempMarker.Width, tempMarker.Height);
      }
      context.restore();*/
    }
    img.src = $("#urlWhy").text();

}

function sharpen(mix) {
    var canvas = document.getElementById("imageToTransform");
    var ctx = canvas.getContext("2d");
    var w = canvas.width;
    var h = canvas.height;

    var weights = [0, -1, 0, -1, 5, -1, 0, -1, 0],
        katet = Math.round(Math.sqrt(weights.length)),
        half = (katet * 0.5) | 0,
        dstData = ctx.createImageData(w, h),
        dstBuff = dstData.data,
        srcBuff = ctx.getImageData(0, 0, w, h).data,
        y = h;

    while (y--) {

        x = w;

        while (x--) {

            var sy = y,
                sx = x,
                dstOff = (y * w + x) * 4,
                r = 0,
                g = 0,
                b = 0,
                a = 0;

            for (var cy = 0; cy < katet; cy++) {
                for (var cx = 0; cx < katet; cx++) {

                    var scy = sy + cy - half;
                    var scx = sx + cx - half;

                    if (scy >= 0 && scy < h && scx >= 0 && scx < w) {

                        var srcOff = (scy * w + scx) * 4;
                        var wt = weights[cy * katet + cx];

                        r += srcBuff[srcOff] * wt;
                        g += srcBuff[srcOff + 1] * wt;
                        b += srcBuff[srcOff + 2] * wt;
                        a += srcBuff[srcOff + 3] * wt;
                    }
                }
            }

            dstBuff[dstOff] = r * mix + srcBuff[dstOff] * (1 - mix);
            dstBuff[dstOff + 1] = g * mix + srcBuff[dstOff + 1] * (1 - mix);
            dstBuff[dstOff + 2] = b * mix + srcBuff[dstOff + 2] * (1 - mix)
            dstBuff[dstOff + 3] = srcBuff[dstOff + 3];
        }
    }

    ctx.putImageData(dstData, 0, 0);
}

window.onload = function(){
    var canvas = document.getElementById("imageToTransform");
    var url = canvas.toDataURL();

    var Markers = new Array();

    var translatePos = {
        x: 0,
        y: 0
    };

    var scale = 1.0;
    var rotate = 0;
    var degree = 15;
    var mix = 20;
    var sharpen = 10;
    var scaleMultiplier = 0.8;
    var startDragOffset = {};
    var mouseDown = false;

    // add button event listeners
    document.getElementById("plus").addEventListener("click", function(){
        scale /= scaleMultiplier;
        draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
    }, false);

    document.getElementById("minus").addEventListener("click", function(){
        scale *= scaleMultiplier;
        draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
    }, false);

    document.getElementById("rotate").addEventListener("click", function(){
        rotate += degree;
        draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
    }, false);

    document.getElementById("mix").addEventListener("click", function(){
      mix += sharpen;
      draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
    }, false);

    document.getElementById('imageFile').addEventListener("click", function(){
      translatePos = {
          x: 0,
          y: 0
      };
      scale = 1.0;
      rotate = 0;
      degree = 15;
      mix = 20;
      sharpen = 10;
      scaleMultiplier = 0.8;
      startDragOffset = {};
      mouseDown = false;

    }, false);

    // add event listeners to handle screen drag
    canvas.addEventListener("mousedown", function(evt){
        //if(evt.which == 1) {
          mouseDown = true;
          startDragOffset.x = evt.clientX - translatePos.x;
          startDragOffset.y = evt.clientY - translatePos.y;
        /*}
        else if (evt.which == 2) {
          var rect = canvas.getBoundingClientRect();
          var mouseXPos = (evt.x - rect.left);
          var mouseYPos = (evt.y - rect.top);
          var marker = new Marker();
          marker.XPos = mouseXPos - (marker.Width / 2);
          marker.YPos = mouseYPos - marker.Height;
          Markers.push(marker);
          draw(scale, translatePos, rotate, Markers, url);
        }*/
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
            draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
        }
    });

    draw(mix * 0.01, scale, translatePos, rotate, Markers, url);
};
