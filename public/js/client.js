
/* blink text */
function blink_text() {
      if($('.blink').length){
        $('.blink').fadeOut(300);
    $('.blink').fadeIn(300);
      }
}
setInterval(blink_text, 1000);

function padTo2Digits(num) {
  return num.toString().padStart(2, '0');
}

function formatDate(date) {
  return (
    [
      date.getFullYear(),
      padTo2Digits(date.getMonth() + 1),
      padTo2Digits(date.getDate()),
    ].join('-') +
    ' ' +
    [
      padTo2Digits(date.getHours()),
      padTo2Digits(date.getMinutes()),
      padTo2Digits(date.getSeconds()),
    ].join(':')
  );
}


$(document).ready(function(){

  // Countdown timer 
  if($('.countdown_timer').length){
    var timer = $('.countdown_timer').data('timer');
    console.log(timer);
    timer = timer.replace(' ', 'T');
    // Set the date we're counting down to
    var countDownDate = new Date(timer).getTime();
    console.log(countDownDate);
    // Update the count down every 1 second
    var x = setInterval(function() {
      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Output the result in an element with id="demo"
      if(document.getElementById("d"))
      document.getElementById("d").innerHTML = days + "days " + hours + "hours "
      + minutes + "min " + seconds + "sec ";
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        if(document.getElementById("d"))
        document.getElementById("d").innerHTML = "";
      }
    }, 2000);
  }
  
});
