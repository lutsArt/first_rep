//!function () {
//	var e = ".prerender {  position: relative; overflow: hidden; }.prerender::before { content: ''; position: absolute; width: 100%; height: 100%; z-index: 100; background-color: #fff; }", t = document.createElement("style");
//	t.type = "text/css", t.styleSheet ? t.styleSheet.cssText = e : t.appendChild(document.createTextNode(e));
//	var n = document.getElementsByTagName("title");
//	n = n[n.length - 1], n.parentNode.insertBefore(t, n), document.documentElement.classList.add("prerender"), document.addEventListener("DOMContentLoaded", function () {
//		document.querySelector(".prerender").classList.remove("prerender")
//	})
//}(window);