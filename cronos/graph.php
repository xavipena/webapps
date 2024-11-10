<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";

$lang = 'ca';
?>
  <style> body { margin: 0; } </style>

  <script src="//unpkg.com/three"></script>
  <script src="//unpkg.com/3d-force-graph"></script>
  <!--<script src="../../dist/3d-force-graph.js"></script>-->
</head>

<body>
  <div id="3d-graph"></div>

  <script>
    const imgs = [
        <?php
            $c = 0;
            include "./includes/selectPhotos.inc.php";
                
            $sql = "select * from crono_photos where IDphoto > 0 $where";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) 
            {
                if ($c) echo ",";
                echo "'".GetPicture($row['IDphoto'],'square')[0]."'";
                $c += 1;
            }
        ?>
    ];

    // Random connected graph
    const gData = {
      nodes: imgs.map((img, id) => ({ id, img })),
      links: [...Array(imgs.length).keys()]
        .filter(id => id)
        .map(id => ({
          source: id,
          target: Math.round(Math.random() * (id-1))
        }))
    };

    const Graph = ForceGraph3D()
      (document.getElementById('3d-graph'))
      .nodeThreeObject(({ img }) => 
      {
        const imgTexture = new THREE.TextureLoader().load(`${img}`);
        imgTexture.colorSpace = THREE.SRGBColorSpace;
        const material = new THREE.SpriteMaterial({ map: imgTexture });
        const sprite = new THREE.Sprite(material);
        sprite.scale.set(12, 12);
        return sprite;
      })
      .graphData(gData)
      .onNodeClick(node => {
          // Aim at node from outside it
          const distance = 40;
          const distRatio = 1 + distance/Math.hypot(node.x, node.y, node.z);

          const newPos = node.x || node.y || node.z
            ? { x: node.x * distRatio, y: node.y * distRatio, z: node.z * distRatio }
            : { x: 0, y: 0, z: distance }; // special case if node is in (0,0,0)

          Graph.cameraPosition(
            newPos, // new position
            node, // lookAt ({ x, y, z })
            3000  // ms transition duration
          );
        });
  </script>
</body>