
let preDebugSizes = document.createElement('pre');
preDebugSizes.style = 'position:fixed;bottom:20px;left:0px;right:0px;margin:auto;text-align:center;background-color:#fffd;width:auto;border:1px solid gray;font-size:small;max-width: max-content;';

const loadDebugSizes = function(){
	if(preDebugSizes)
	
	preDebugSizes.innerHTML = "scroll\t / \tview\t / \tscreen<br>width:"+document.documentElement.scrollWidth+" / width:"+window.innerWidth+" / width:"+window.screen.availWidth
		+"<br>height:"+document.documentElement.scrollHeight+" / height:"+window.innerHeight+" / height:"+window.screen.availHeight;
};

document.addEventListener('DOMContentLoaded',function(){document.body.appendChild(preDebugSizes);loadDebugSizes()});
setInterval(loadDebugSizes, 10000);