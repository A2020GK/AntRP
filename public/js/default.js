const sidebar = document.querySelector(".sidebar");
// This niga code here opens and closes the sidebar
// Yes I'm know it's unoptimised cause reads selector again every time...
// BUT WHO THE FUCK CARES? IT's MY PROJECT, FUCK OFF
document.querySelector(".menu-toggle").addEventListener("click",event=>{
    sidebar.classList.add("active");
});
document.querySelector(".menu-close").addEventListener("click",event=>{
    sidebar.classList.remove("active")
});