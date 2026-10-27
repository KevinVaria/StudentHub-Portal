document.addEventListener("DOMContentLoaded", () => {
  const body=document.body;
  const theme=document.createElement("button"); theme.textContent="🌙 Theme"; theme.id="themeBtn";
  document.querySelector("header").appendChild(theme);
  theme.addEventListener("click",()=>{body.classList.toggle("dark");localStorage.setItem("theme",body.classList.contains("dark")?"dark":"light")});
  if(localStorage.getItem("theme")==="dark") body.classList.add("dark");

  const banner=document.createElement("div"); banner.className="notice"; banner.textContent="📢 Welcome to StudentHub! New campus events are available.";
  document.querySelector("main").prepend(banner);

  document.querySelectorAll("details").forEach(d=>d.addEventListener("toggle",()=> {
    if(d.open) document.querySelectorAll("details").forEach(x=>{if(x!==d)x.open=false});
  }));

  const modal=document.createElement("div"); modal.className="modal hidden";
  modal.innerHTML='<div class="modal-box"><h2>StudentHub</h2><p>Registration opens for upcoming events.</p><button id="closeModal">Close</button></div>';
  document.body.appendChild(modal);
  const open=document.createElement("button"); open.textContent="Open Notice"; open.onclick=()=>modal.classList.remove("hidden");
  document.querySelector("main").appendChild(open);
  document.getElementById("closeModal").onclick=()=>modal.classList.add("hidden");

  let slides=["🎓 Welcome to StudentHub","📅 Discover Campus Events","💡 Share Your Feedback"],i=0;
  const slider=document.createElement("div"); slider.className="slider"; slider.textContent=slides[0];
  document.querySelector("main").appendChild(slider);
  setInterval(()=>{i=(i+1)%slides.length;slider.textContent=slides[i]},2500);
});
