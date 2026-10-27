let all=[], filtered=[], page=1, perPage=3;
const events=document.getElementById("events");
fetch("data/events.json").then(r=>r.json()).then(data=>{all=data;apply()}).catch(()=>events.textContent="Could not load events.");
function apply(){const q=document.getElementById("search").value.toLowerCase(), c=document.getElementById("category").value, s=document.getElementById("sort").value;
 filtered=all.filter(x=>(x.title.toLowerCase().includes(q)||x.location.toLowerCase().includes(q))&&(c==="all"||x.category===c));
 filtered.sort((a,b)=>s==="title"?a.title.localeCompare(b.title):a.date.localeCompare(b.date)); page=1;render()}
function render(){const start=(page-1)*perPage;events.innerHTML=filtered.slice(start,start+perPage).map(x=>`<article class="card"><h2>${x.title}</h2><p><b>Category:</b> ${x.category}</p><p><b>Date:</b> ${x.date}</p><p><b>Location:</b> ${x.location}</p><button onclick="alert('Selected: ${x.title}')">Register</button></article>`).join("")||"<p>No events found.</p>";
const pages=Math.ceil(filtered.length/perPage);document.getElementById("pagination").innerHTML=Array.from({length:pages},(_,i)=>`<button onclick="page=${i+1};render()">${i+1}</button>`).join(" ")}
document.getElementById("search").oninput=apply;document.getElementById("category").onchange=apply;document.getElementById("sort").onchange=apply;
