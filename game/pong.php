<!DOCTYPE html>
<html>
<head>
<script>
//modified from http://jsfiddle.net/bencentra/q1s8gmqv/?utm_source=website&utm_medium=embed&utm_campaign=q1s8gmqv
var canvas;
var context;
var loop;
var leftPaddle;
var rightPaddle;
var ball;
var ball2;
var paddleWidth = 25;
var paddleHeight = 100;
var ballSize = 10;
var ballSize2=25;
var ballSpeed= 2;
var ballSpeed2= 0.75;
var paddleSpeed = 2;
var drawables = [];
var myMusic;
// Key Codes
const W = 87;
const S = 83;
const UP = 38;
const DOWN = 40;
let vision = .6;

// Keep track of pressed keys
var keys = {
    W: false,
    S: false,
    UP: false,
    DOWN: false
};

// Keep track of the score
var leftScore = 0;
var rightScore = 0;
function init() {
    canvas = document.getElementById("board");
    if (canvas.getContext) {
        context = canvas.getContext("2d");
        leftPaddle = makeRect(25, canvas.height / 2 - paddleHeight / 2, paddleWidth, paddleHeight, paddleSpeed, '#BC0000');
        rightPaddle = makeRect(canvas.width - paddleWidth - 25, canvas.height / 2 - paddleHeight / 2, paddleWidth, paddleHeight, paddleSpeed, '#0000BC');
        ball = makeRect(0, 0, ballSize, ballSize, ballSpeed, '#000000');
        ball2 = makeRect(0, 0, ballSize2, ballSize2, ballSpeed2, '#000000');
        drawables.push(leftPaddle);
        drawables.push(rightPaddle);
        drawables.push(ball);
        drawables.push(ball2);
        console.log(drawables);
        resetBall();
        attachKeyListeners();
        loop = window.setInterval(gameLoop, 16); //16ms
        canvas.focus();
    }
}
function resetBall() {
    ball.x = canvas.width / 2 - ball.w / 2;
    ball.y = canvas.height / 2 - ball.w / 2;
    // Modify the ball object to have two speed properties, one for X and one for Y
    ball.sX = ballSpeed;
    ball.sY = ballSpeed / 2;

    // Randomize initial direction
    if (Math.random() > 0.5) {
        ball.sX *= -1;
    }
    // Randomize initial direction
    if (Math.random() > 0.5) {
        ball.sY *= -1;
    }
    ball2.x = canvas.width / 2 - ball2.w / 2;
    ball2.y = canvas.height / 2 - ball2.w / 2;
    // Modify the ball object to have two speed properties, one for X and one for Y
    ball2.sX = ballSpeed2;
    ball2.sY = ballSpeed2 / 2;

    // Randomize initial direction
    if (Math.random() > 0.5) {
        ball2.sX *= -1;
    }
    // Randomize initial direction
    if (Math.random() > 0.5) {
        ball2.sY *= -1;
    }
}
// Bounce the ball off of a paddle
function bounceBall() {
    // Increase and reverse the X speed
    if (ball.sX > 0) {
        ball.sX += 1;
        // Add some "spin"
        if (keys.UP) {
            ball.sY -= 1;
        } else if (keys.DOWN) {
            ball.sY += 1;
        }
    } else {
        ball.sX -= 1;
        // Add some "spin"
        if (keys.W) {
            ball.sY -= 1;
        } else if (keys.S) {
            ball.sY += 1
        }
    }
    ball.sX *= -1;
}
function bounceBall2() {
    // Increase and reverse the X speed
    if (ball2.sX > 0) {
        ball2.sX += 1;
        // Add some "spin"
        if (keys.UP) {
            ball2.sY -= 1;
        } else if (keys.DOWN) {
            ball2.sY += 1;
        }
    } else {
        ball2.sX -= 1;
        // Add some "spin"
        if (keys.W) {
            ball2.sY -= 1;
        } else if (keys.S) {
            ball2.sY += 1
        }
    }
    ball2.sX *= -1;
}
function attachKeyListeners() {
    // Listen for keydown events
    window.addEventListener('keydown', function (e) {
        console.log("keydown", e);
        if (e.keyCode === W) {
            keys.W = true;
        }
        if (e.keyCode === S) {
            keys.S = true;
        }
        if (e.keyCode === UP) {
            keys.UP = true;
        }
        if (e.keyCode === DOWN) {
            keys.DOWN = true;
        }
        console.log(keys);
    });
    window.addEventListener('keyup', function (e) {
        console.log("keyup", e);
        if (e.keyCode === W) {
            keys.W = false;
        }
        if (e.keyCode === S) {
            keys.S = false;
        }
        if (e.keyCode === UP) {
            keys.UP = false;
        }
        if (e.keyCode === DOWN) {
            keys.DOWN = false;
        }
        console.log(keys);
    });
}
// Create a rectangle object - for paddles, ball, etc
function makeRect(x, y, width, height, speed, color) {
    if (!color)
        color = '#000000';
    return {
        x: x,
        y: y,
        w: width,
        h: height,
        s: speed,
        c: color,
        draw: function () {
            context.fillStyle = this.c;
            context.fillRect(this.x, this.y, this.w, this.h);
        }
    };
}
function doAI() {
    //rightPaddle.y = ball.y;//perfect AI
    //rightPaddle.y = ball2.y;//perfect AI
    vision = .6;
    let diff = leftScore - rightScore;
    if(diff < 0){
        vision -= .1*diff;
    }
    if(diff > 0){
        vision -= .1* diff;
    }
   if (ball.x >= canvas.width * vision) {
       
        let paddleHalf = paddleHeight / 2;
        if (ball.y > rightPaddle.y + paddleHalf) {
            rightPaddle.y += rightPaddle.s;
        } else if (ball.y < rightPaddle.y) {
            rightPaddle.y -= rightPaddle.s;
        }
    }
   
    if (ball2.x >= canvas.width * vision) {
        
         let paddleHalf = paddleHeight / 2;
         if (ball2.y > rightPaddle.y + paddleHalf) {
             rightPaddle.y += rightPaddle.s;
         } else if (ball2.y < rightPaddle.y) {
             rightPaddle.y -= rightPaddle.s;
         }
     }
    clampToCanvas(rightPaddle);
}
function movePaddle() {
    if (keys.W) {
        leftPaddle.y -= leftPaddle.s;
    }
    if (keys.S) {
        leftPaddle.y += leftPaddle.s;
    }
    if (keys.UP) {
        leftPaddle.y -= leftPaddle.s;
    }
    if (keys.DOWN) {
        leftPaddle.y += leftPaddle.s;
    }
    clampToCanvas(leftPaddle);
}
function clampToCanvas(paddle) {
    if (paddle.y < 0) {
        paddle.y = 0;
    }
    if (paddle.y + paddle.h > canvas.height) {
        paddle.y = canvas.height - paddle.h;
    }
}
function moveBall() {
    // Move the ball
    ball.x += ball.sX;
    ball.y += ball.sY;
    // Bounce the ball off the top/bottom
    if (ball.y < 0 || ball.y + ball.h > canvas.height) {
        ball.sY *= -1;
    }
    ball2.x += ball2.sX;
    ball2.y += ball2.sY;
    // Bounce the ball off the top/bottom
    if (ball2.y < 0 || ball2.y + ball2.h > canvas.height) {
        ball2.sY *= -1;
    }
}
function checkPaddleCollision() {
    // Bounce the ball off the paddles
    if (ball.y + ball.h / 2 >= leftPaddle.y && ball.y + ball.h / 2 <= leftPaddle.y + leftPaddle.h) {
        if (ball.x <= leftPaddle.x + leftPaddle.w) {
            bounceBall();
        }
    }
    if (ball.y + ball.h / 2 >= rightPaddle.y && ball.y + ball.h / 2 <= rightPaddle.y + rightPaddle.h) {
        if (ball.x + ball.w >= rightPaddle.x) {
            bounceBall();
        }
    }
    if (ball2.y + ball2.h / 2 >= leftPaddle.y && ball2.y + ball2.h / 2 <= leftPaddle.y + leftPaddle.h) {
        if (ball2.x <= leftPaddle.x + leftPaddle.w) {
            bounceBall2();
        }
    }
    if (ball2.y + ball2.h / 2 >= rightPaddle.y && ball2.y + ball2.h / 2 <= rightPaddle.y + rightPaddle.h) {
        if (ball2.x + ball2.w >= rightPaddle.x) {
            bounceBall2();
        }
    }
}
function checkScore() {
    // Score if the ball goes past a paddle
    if (ball.x < leftPaddle.x) {
        rightScore++;
        resetBall();
        ball.sX *= -1;
    } else if (ball.x + ball.w > rightPaddle.x + rightPaddle.w) {
        leftScore++;
        resetBall();
        ball.sX *= -1;
    }
    if (ball2.x < leftPaddle.x) {
        rightScore++;
        resetBall();
        ball2.sX *= -1;
    } else if (ball2.x + ball2.w > rightPaddle.x + rightPaddle.w) {
        leftScore++;
        resetBall();
        ball2.sX *= -1;
    }
}
function drawScores() {
    // Draw the scores
    context.fillStyle = '#000000';
    context.font = '24px Arial';
    context.textAlign = 'left';
    context.fillText('Score: ' + leftScore, 5, 24);
    context.textAlign = 'right';
    context.fillText('Score: ' + rightScore, canvas.width - 5, 24);

    context.fillText("Vision: " + vision, 200, 50);
}
function erase() {
    context.fillStyle = '#FFFFFF';
    context.fillRect(0, 0, canvas.width, canvas.height);
}
//function sound(src) {
//  this.sound = document.createElement("audio");
//  this.sound.src = src;
//  this.sound.setAttribute("preload", "auto");
//  this.sound.setAttribute("controls", "none");
//  this.sound.style.display = "none";
//  document.body.appendChild(this.sound);
//  this.play = function(){
//    this.sound.play();
//  }
//  this.stop = function(){
//    this.sound.pause();
//  }
//}
function gameLoop() {
//    myMusic = new sound("background.mp3");
//    myMusic.play();
    erase();
    movePaddle();
    doAI();
    moveBall();
    

    checkPaddleCollision();
    checkScore();
    drawScores();
    //draw stuff
    for (let i = 0; i < drawables.length; i++) {
        drawables[i].draw();
    }
}

</script>
</head>
<body onload="init();">
    <a href="http://bencentra.com/2017-07-11-basic-html5-canvas-games.html">Collection of Canvas based games by Ben Centra</a>
    <main>
        <canvas id="board" width="600px" height="600px" style="border: 1px solid black;">
        
        </canvas>
    </main>
</body>
<audio autoplay loop>
      <source src="background.mp3">
  </audio
</html>
