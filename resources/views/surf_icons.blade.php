<img src="/surf_icons?t={{ time() }}" height="48" id="icons" usemap="#iconsmap" />
<map name="iconsmap">
  <area shape="rect" coords="0, 0, 48, 48" href="#" onclick="validateClick('{{ session('icon_ids')[0] }}')" alt="">
  <area shape="rect" coords="68, 0, 116, 48" href="#" onclick="validateClick('{{ session('icon_ids')[1] }}')" alt="">
  <area shape="rect" coords="136, 0, 184, 48" href="#" onclick="validateClick('{{ session('icon_ids')[2] }}')" alt="">
  <area shape="rect" coords="204, 0, 252, 48" href="#" onclick="validateClick('{{ session('icon_ids')[3] }}')" alt="">
  <area shape="rect" coords="272, 0, 320, 48" href="#" onclick="validateClick('{{ session('icon_ids')[4] }}')" alt="">
</map>