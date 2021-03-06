var width = 900, height = 500;

var color = d3.scale.category20();

var force = d3.layout.force().charge(-120)//120
.linkDistance(function(d) {
	return d.value < 20 ? d.value * 20 : d.value * 5;
})//30
.size([width, height]);

var svg = d3.select("body").append("svg").attr("width", width).attr("height", height).append('g');

d3.json("miserables.json", function(error, graph) {
	if (error)
		throw error;

	force.nodes(graph.nodes).links(graph.links).start();

	var link = svg.selectAll(".link").data(graph.links).enter().append("line").attr("class", "link").style("stroke-width", function(d) {
		return Math.sqrt(d.value * 1 / 20);
	});

	var edgepaths = svg.selectAll(".edgepath").data(graph.links).enter().append('path').attr({
		'd' : function(d) {
			return 'M ' + d.source.x + ' ' + d.source.y + ' L ' + d.target.x + ' ' + d.target.y;
		},
		'class' : 'edgepath',
		'fill-opacity' : 0,
		'stroke-opacity' : 0,
		'fill' : 'blue',
		'stroke' : 'red',
		'id' : function(d, i) {
			return 'edgepath' + i;
		}
	}).style("pointer-events", "none");

	var edgelabels = svg.selectAll(".edgelabel").data(graph.links).enter().append('text').style("pointer-events", "none").attr({
		'class' : 'edgelabel',
		'id' : function(d, i) {
			return 'edgelabel' + i;
		},
		'dx' : 80,
		'dy' : 20,
		'font-size' : 16,
		'fill' : '#aaa'
	});

	edgelabels.append('textPath').attr('xlink:href', function(d, i) {
		return '#edgepath' + i;
	}).style("pointer-events", "none").text(function(d, i) {
		return d.value;
	});

	var node_drag = d3.behavior.drag().on("dragstart", dragstart).on("drag", dragmove).on("dragend", dragend);

	function dragstart(d, i) {
		force.stop();
		// stops the force auto positioning before you start dragging
	}

	function dragmove(d, i) {
		d.px += d3.event.dx;
		d.py += d3.event.dy;
		d.x += d3.event.dx;
		d.y += d3.event.dy;
		force.start();
		// this is the key to make it work together with updating both px,py,x,y on d !
	}

	function dragend(d, i) {
		d.fixed = true;
		// of course set the node to fixed so the force doesn't include the node in its auto positioning stuff
		force.start();
		force.resume();
	}

	var node = svg.selectAll(".node").data(graph.nodes).enter().append("circle").attr("class", "node").attr("r", 15).style("fill", function(d) {
		return color(d.group);
	}).call(node_drag);

	var nodelabels = svg.selectAll(".nodelabel").data(graph.nodes).enter().append("text").attr({
		"x" : function(d) {
			return d.x;
		},
		"y" : function(d) {
			return d.y;
		},
		"class" : "nodelabel",
		"stroke" : "black"
	}).text(function(d) {
		return d.name;
	});

	force.on("tick", function() {
		link.attr("x1", function(d) {
			return d.source.x;
		}).attr("y1", function(d) {
			return d.source.y;
		}).attr("x2", function(d) {
			return d.target.x;
		}).attr("y2", function(d) {
			return d.target.y;
		});

		edgepaths.attr('d', function(d) {
			var path = 'M ' + d.source.x + ' ' + d.source.y + ' L ' + d.target.x + ' ' + d.target.y;
			//console.log(d)
			return path;
		});

		edgelabels.attr('transform', function(d, i) {
			if (d.target.x < d.source.x) {
				bbox = this.getBBox();
				rx = bbox.x + bbox.width / 2;
				ry = bbox.y + bbox.height / 2;
				return 'rotate(180 ' + rx + ' ' + ry + ')';
			} else {
				return 'rotate(0)';
			}
		});

		node.attr("cx", function(d) {
			return d.x;
		}).attr("cy", function(d) {
			return d.y;
		});

		nodelabels.attr("x", function(d) {
			return d.x + 20;
		}).attr("y", function(d) {
			return d.y;
		});

	});

});
