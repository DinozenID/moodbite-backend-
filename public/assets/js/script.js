document.querySelectorAll(".budget-card").forEach(card=>{

card.addEventListener("click",()=>{

document.querySelectorAll(".budget-card")
.forEach(c=>c.classList.remove("active"));

card.classList.add("active");

});

});

const generateBtn=document.getElementById("generateBtn");

if(generateBtn){

generateBtn.addEventListener("click",()=>{

document
.getElementById("recommendationResult")
.classList.remove("d-none");

document
.getElementById("recommendationResult")
.scrollIntoView({

behavior:"smooth"

});

});

}