<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
#adam{
	background:url(includes/capguy-walk.png);
	width: 180px;
	height: 330px;
	animation: walk-east 1.0s steps(8) infinite;
  animation-play-state: paused;

}
@keyframes walk-east {
	from { background-position: 0px; }
	to { background-position: -1472px; }
}
#adam:hover{
  animation-play-state: running;
}
</style>
  </head>
  <body>
<div id="adam"></div>
  </body>
</html>
