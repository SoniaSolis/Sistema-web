const contador1 = document.getElementById('contador1');
const contador2 = document.getElementById('contador2');

let count1 = 0;
let count2 = 0;

let intervalo1 = setInterval(incrementar1, 1000);
let intervalo2 = setInterval(incrementar2, 1000);

function incrementar1() {
  count1 += 0.1;
  contador1.textContent = count1.toFixed(1);

  if (count1 >= 4.0) {
    clearInterval(intervalo1);
  }
}

function incrementar2() {
  count2 += 0.1;
  contador2.textContent = count2.toFixed(1) + "ME GUSTA";

  if (count2 >= 3.5) {
    clearInterval(intervalo2);
  }
}