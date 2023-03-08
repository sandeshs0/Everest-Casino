// Immediately invoked function expression
// to not pollute the global scope
(function() {
  const wheel = document.querySelector('.wheel');
  const startButton = document.querySelector('.button');
  const audio=document.getElementById("sound");
  const second=document.getElementById("sound2");
  const third=document.getElementById("sound3");
  const confetti=document.getElementById("confetti-container")
  let deg = 0;

  startButton.addEventListener('click', () => {
    
    startButton.style.pointerEvents = 'none';

    deg = Math.floor(5000 + Math.random() * 5000);

    wheel.style.transition = 'all 6.47s ease-in-out';
    wheel.style.transform = `rotate(${deg}deg)`;
    wheel.classList.add('blur');
  });

  wheel.addEventListener('transitionend', () => {
    wheel.classList.remove('blur');
    startButton.style.pointerEvents = 'auto';
    wheel.style.transition = 'none';
    const actualDeg = deg % 360;
    wheel.style.transform = `rotate(${actualDeg}deg)`;
    confetti.style.opacity="100%"
  
    setTimeout(function(){
      confetti.style.opacity="0%";
      
    },8000)
    second.play();
    setTimeout(function(){
      audio.play();
      
    },5000)
    setTimeout(function(){
      third.play();
      
    },7000)

  });
})();
