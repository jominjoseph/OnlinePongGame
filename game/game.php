<!DOCTYPE html>
<html>
<canvas id="canvas" width="600" height="400" tabindex="1"></canvas>
<head>
<script>

// Collect The Square game

// Get a reference to the canvas DOM element
var canvas = document.getElementById('canvas');
// Get the canvas drawing context

// Your score
var score = 0;
let didSend = false;
// Properties for your square
var x = 50; // X position
var y = 100; // Y position
var speed = 6; // Distance to move each frame
var sideLength = 75; // Length of each side of the square

// FLags to track which keys are pressed
var down = false;
var up = false;
var right = false;
var left = false;

// Properties for the target square
var targetX = 0;
var targetY = 0;
var negativesquarex = 0;
var negativesquarey = 0;
var targetLength = 25;
var context = canvas.getContext('2d');
// Determine if number a is within the range b to c (exclusive)
function isWithin(a, b, c) {
  return (a > b && a < c);
}


// Countdown timer (in seconds)
var countdown = 30;
// ID to track the setTimeout
var id = null;


// Listen for keydown events
window.addEventListener('keydown', function(event) {
  event.preventDefault();
  console.log(event.key, event.keyCode);
  if (event.keyCode === 40) { // DOWN
    down = true;
  }
  if (event.keyCode === 38) { // UP
    up = true;
  }
  if (event.keyCode === 37) { // LEFT
    left = true;
  }
  if (event.keyCode === 39) { // RIGHT
    right = true;
  }
});
//var plane = new Image();
//plane.src= "plane.png"
//plane.addEventListener("load",function(){context.drawImage(plane,0,0)},false);
// Listen for keyup events
window.addEventListener('keyup', function(event) {
  event.preventDefault();
  console.log(event.key, event.keyCode);
  if (event.keyCode === 40) { // DOWN
    down = false;
  }
  if (event.keyCode === 38) { // UP
    up = false;
  }
  if (event.keyCode === 37) { // LEFT
    left = false;
  }
  if (event.keyCode === 39) { // RIGHT
    right = false;
  }
});

// Show the start menu
function menu() {
  erase();
  context.fillStyle = '#000000';
  context.font = '36px Arial';
  context.textAlign = 'center';
  context.fillText('Collect the Square!', canvas.width / 2, canvas.height / 4);
  context.font = '24px Arial';
  context.fillText('Click to Start', canvas.width / 2, canvas.height / 2);
  context.font = '18px Arial'
  context.fillText('Use the arrow keys to move', canvas.width / 2, (canvas.height / 4) * 3);
  // Start the game on a click
  window.addEventListener('click', startGame);
}

// Start the game
function startGame() {
    // Reduce the countdown timer ever second
  id = setInterval(function() {
    countdown--;
  }, 1000)
  // Stop listening for click events
  canvas.removeEventListener('click', startGame);
  // Put the target at a random starting point
    moveTarget();
  // Kick off the draw loop
  draw();
    let ajax = new XMLHttpRequest();
                    ajax.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let resp = this.responseText;
                            resp = JSON.parse(resp);
                            //JSON.stringify(resp);
                            console.log("response", resp, resp["data"], resp.data);
                            if(resp.status === 200){
                                speed = resp.data.speed;
                               
                              
                            }
                            //start();
                        }
                    };
                    ajax.open("GET", "api/get_settings.php", true);
                    ajax.send();
    }
                        
// Show the game over screen

function saveScore(){
            if(!didSend){//mutex boolean to block multiple calls from sending repeated data
                didSend = true;
        //        window.clearInterval(loop);//stops the game loop
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        let resp = this.responseText;
                        resp = JSON.parse(resp);
                        //JSON.stringify(resp);
                        console.log("response", resp, resp["data"], resp.data);
                        
                    }
                };
                xhttp.open("POST", "api/save_score.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("score="+score);
                }
}
function endGame() {
    // Stop the countdown
  clearInterval(id);
  // Display the final score
  erase();
    context.clearRect(0, 0, 600, 400);
  context.fillStyle = '#000000';
  context.font = '24px Arial';
  context.textAlign = 'center';
  context.fillText('Final Score: ' + score, canvas.width / 2, canvas.height / 2);
    saveScore();
}

// Move the target square to a random position
function movenegative(){
    negativesquarex = Math.round(Math.random() * canvas.width - targetLength);
    negativesquarex = Math.round(Math.random() * canvas.width - targetLength);
}

function moveTarget() {
  targetX = Math.round(Math.random() * canvas.width - targetLength);
  targetY = Math.round(Math.random() * canvas.height - targetLength)

}

// Clear the canvas
function erase() {
  context.fillStyle = '#FFFFFF';
  context.fillRect(0, 0, 600, 400);


}

// The main draw loop
function draw() {

  erase();
    
  // Move the square
  if (down) {
    y += speed;
  }
  if (up) {
    y -= speed;
  }
  if (right) {
    x += speed;
  }
  if (left) {
    x -= speed;
  }
  // Keep the square within the bounds
  if (y + sideLength > canvas.height) {
    y = canvas.height - sideLength;
  }
  if (y < 0) {
    y = 0;
  }
  if (x < 0) {
    x = 0;
  }
  if (x + sideLength > canvas.width) {
    x = canvas.width - sideLength;
  }
  // Collide with the target
  if (isWithin(targetX, x, x + sideLength) || isWithin(targetX + targetLength, x, x + sideLength)) { // X
    if (isWithin(targetY, y, y + sideLength) || isWithin(targetY + targetLength, y, y + sideLength)) { // Y
      // Respawn the target
      moveTarget();
    movenegative();
      // Increase the score
      score++;
    }
  }
    if (isWithin(negativesquarex, x, x + sideLength) || isWithin(negativesquarex + targetLength, x, x + sideLength)) { // X
      if (isWithin(negativesquarey, y, y + sideLength) || isWithin(negativesquarey+ targetLength, y, y + sideLength)) { // Y
        // Respawn the target
        movenegative();
        // Increase the score
        score--;
      }
    }
    
  // Draw the square'=
    var plane = new Image();
    plane.src= "plane.png"
    context.drawImage(plane,x,y,sideLength,sideLength)

//    var plane = new Image();
//    plane.src= "plane.png"
//    plane.addEventListener("load",function(){context.drawImage(plane,x,y,sideLength,sideLength)},false);
  context.fillStyle = '#00FF00';
  context.fillRect(targetX, targetY, targetLength, targetLength);
    context.fillStyle = '#FF0000';
    context.fillRect(negativesquarex, negativesquarey, targetLength, targetLength);
    

  // Draw the score and time remaining
  context.fillStyle = '#000000';
  context.font = '24px Arial';
  context.textAlign = 'left';
  context.fillText('Score: ' + score, 10, 24);
  context.fillText('Time Remaining: ' + countdown, 10, 50);
  // End the game or keep playing
  if (countdown <= 0) {
    endGame();
  } else {
    window.requestAnimationFrame(draw);
  }
}

menu();
canvas.focus();
</script>
</head>

</html>


