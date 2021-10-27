<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="jquery.min.js"></script>
  </head>
  <body>
	<header>
		<img id="udeLogo" src="UDE-logo-claim.svg" align="left" width="230" height="90" alt="Logo der Universität Duisburg-Essen"> 
		<img id="bifoLogo" src="bifo_logo.png" align="right" width="230" height="90" alt="Logo der AG Bildungsforschung"> 
	</header>
	<div class="inhalt">
	<script src="d3.v3.min.js" charset="utf-8"></script>
    <script src="jquery-1.11.0.js"></script>
    <script type="text/javascript">
    
      // Prevent window close
      var hook = true;
      window.onbeforeunload = function() {
        if (hook) {       
          return "Are you sure that you want to end this survey? All of your answers will be lost.";
        }
      }
      function unhook() {
        hook=false;
      }
      
      var bodyWidth = $(document).width();
      var bodyHeight = $(document).height() - 20;
      if (bodyWidth < 800) bodyWidth = 800;
      if (bodyHeight < 750) bodyHeight = 750;
      var center = bodyWidth / 2;
      var middle = bodyHeight / 200;
      
      var textWidth = 800;
      var text_offset_top = 60;
      var title_offset_top = 70;
      var lineHeight = 18;
      
      var q_window_width = 100,
          q_window_height = 100,
          backdrop_width = 500;

      // left and top values for individual questions
      var question_lnum = center - (textWidth / 2);
      var string_l = question_lnum.toString();
      var string_t = "100px";	//vorher 200px
      var string_r_t = "45%",
          q_margin_top = 200,
          q_margin_top_str = q_margin_top.toString();

      // bar with boxes for answers
      var boxbar_margin = 10,
          boxbar_label_margin = 3,	  
          bar_target_height = 100,
          bar_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar4_target_width = ((bodyWidth - (boxbar_margin * 3) - 20) / 4),
          bar5_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar6_target_width = ((bodyWidth - (boxbar_margin * 5) - 20) / 6),
		  bar8_target_width = ((bodyWidth - (boxbar_margin * 7) - 20) /8), 
          bar_label_height = 40,//25
          boxbar_offset_x = 10, 
          boxbar_offset_y = bodyHeight - bar_target_height - 100;

	  var currSlide = 0;
      var numFriends = 0;
      var askedAbout = 0;
      var checked = false;
      var skipped = false;
      var currNode = null;
	  
	  var p1_name = [];
	  var p2_name = [];
	  var p3_name = [];
	  var p4_name = [];
	  
	  var p1_link = [];
	  var p2_link = [];
	  var p3_link = [];
	  var p4_link = [];
	  
	  var p1_dd1 = [];
	  var p2_dd1 = [];	  
	  var p3_dd1 = [];
	  var p4_dd1 = [];
	  
	  var p1_dd2 = [];
	  var p2_dd2 = [];
	  var p3_dd2 = [];
	  var p4_dd2 = [];	

	  var nodes1 = [];
	  var nodes2 = [];
	  var nodes3 = [];
	  var nodes4 = [];
		
	  var artKommunikation = document.getElementById("artKommunikation"),
		  themaErhalteneInfos = document.getElementById("themaErhalteneInfos"),
		  digiSchulentwicklung = document.getElementById("digiSchulentwicklung"),
		  funktionenPersonen = document.getElementById("funktionenPersonen"),
		  wahrgenLernmöglichkeiten = document.getElementById("wahrgenLernmöglichkeiten");		
		  
	  var erhInfoInput = document.getElementById("myText");
	  var inputFunktionen = document.getElementById("weitereFunktionen");
	  var teilnehmercodeInput = document.getElementById("teilnehmercodeInput");
		 
      var nodeColor = '#9CD4D4',
          femaleColor = '#FFCCFF';

      var startTime;
      var results = [];
	  
	  var kommForm = document.getElementById("artDerKommunikation");
	  
      
      //--------------------------------
      // Declaration of graph properties
      //--------------------------------

      var svg = d3.select("body").append("svg")
        .attr("width", bodyWidth)
        .attr("height", bodyHeight)
        .on("contextmenu", function() {d3.event.preventDefault()});					

	  var start_node = {x:bodyWidth / 2, 
                  y:bodyHeight / 2.2, 
                  fixed: true, 
                  name:"Sie", 
                  id:0, 
                  gender:"", 
                  age:0,
                  race:"",
                  religion:"", 
                  surveyTime:0,
                  sawStats:false,
                  edu:null, 
                  freq:null,
                  friendsWith:"",
                  haveBlackFriends:"",
                  friendsBlackFriends:"",
                  artDerKommunikation:"",
                  seeBlackFriends:"",
                  enjoy:"",
                  like:"",
                  interest:"",
                  motivation:""};
      
	  var force;
		
        
      var nodes,
          links,
          node,
          link;

//removes nodes and links and refreshes	with a single start-node ("Sie")	  
		function resetGraph() {
			svg.selectAll(".node").remove();
			svg.selectAll(".link").remove();
			
			 force = d3.layout.force()
				.size([bodyWidth, bodyHeight])
				.nodes([start_node]) // initialize with a single node
				.linkDistance(100)
				.charge(-1500)
				.on("tick", tick);
		
        
			  nodes = force.nodes();
			  links = force.links();
			  node = svg.selectAll(".node");
			  link = svg.selectAll(".link");
			  
			  force.start();
		}
		
	resetGraph();

      //--------------------------------
      // Declaration of slides and boxes
      //--------------------------------

      // Slide 0
      
      // Catch Internet Explorer users; incompatible browser
      if (isIE()) {
        var slide_0 = d3.select("svg").append("g")
          .attr("id", "slide0");
        slide_0.append("rect")
          .style("fill", "white")
          .attr("x", 0)
          .attr("y", 0)
          .attr("width", bodyWidth)
          .attr("height", bodyHeight);
        slide_0.append("text")
          .attr("class", "lead")
          .text("Ihr Browser wird nicht unterstützt.")
          .attr("x", center - 170)
          .attr("y", title_offset_top);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight)
          .text("Bitte benutzen Sie einen anderen Browser für diese Umfrage.")
          .call(wrap, textWidth);
        document.getElementById("Next").style.display="none";
      } else {
        var slide_0 = d3.select("svg").append("g")
          .attr("id", "slide0");
        slide_0.append("rect")
          .style("fill", "white")
          .attr("x", 0)
          .attr("y", 0)
          .attr("width", bodyWidth)
          .attr("height", bodyHeight);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", lineHeight)
          .text("Sehr geehrte Lehrer*innen,")
          .call(wrap, textWidth);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", lineHeight + lineHeight*2)
          .text("ganz herzlichen Dank, dass Sie sich an unserer Erhebung beteiligen!")
          .call(wrap, textWidth);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", lineHeight + lineHeight * 4)
          .text("Die Erhebung findet im Rahmen des Forschungsprojekts „Digitale Schulentwicklung in Netzwerken - DigiSchulNet“ statt, das gemeinsam von Learning Lab und der Arbeitsgruppe Bildungsforschung der Universität Duisburg-Essen durchgeführt und durch das Bundesministerium für Bildung und Forschung gefördert wird.")
		  .call(wrap, textWidth);
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", lineHeight + lineHeight * 8)
          .text("Ziel des Vorhabens ist die Analyse des Transfers von Wissen, Praxisbeispielen und Handlungsstrategien innerhalb bestehender Schulnetzwerke und in die beteiligten Schulen hinein. Dafür werden Schulnetzwerke mit digitalem Schwerpunkt sowie ein Schulnetzwerk mit einem anderen innovativen, nicht-digitalen Schwerpunkt in den Blick genommen. Hier sollen vor allem die Kommunikationsbeziehungen innerhalb der Schulnetzwerke sowie die Weitergabe des dort erworbenen Wissens in die Einzelschule untersucht werden.")
		  .call(wrap, textWidth);
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", lineHeight + lineHeight * 14)
          .text("Unter dem Link 'Datenschutzerklärung anzeigen' informieren wir Sie noch einmal über den datenschutzrechtskonformen Umgang mit Ihren personenbezogenen Daten. Ihre Zustimmung zur Teilnahme an unserer Studie sowie zur Verwendung Ihrer Daten für die angegebenen Zwecke von der letzten Erhebungswelle bleibt wirksam. Bei Rückfragen oder Verständnisschwierigkeiten können Sie sich gerne bei Dr. Marco Hasselkuß (marco.hasselkuss@uni-due.de) melden.")
		  .call(wrap, textWidth);  
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", title_offset_top + lineHeight * 17)
          .text("Bitte geben Sie Ihren persönlichen Zugriffscode in das Kästchen unten ein und klicken Sie Weiter. Wir danken Ihnen nochmals herzlich für Ihre Mitwirkung und Ihr Vertrauen in unsere Arbeit. ")
		  .call(wrap, textWidth);
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", title_offset_top + lineHeight * 20)
          .text("Mit freundlichen Grüßen ")
		  .call(wrap, textWidth); 
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", title_offset_top + lineHeight * 21)
          .text("Prof. Dr. Isabell van Ackeren, ")
		  .call(wrap, textWidth); 
		slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", title_offset_top + lineHeight * 22)
          .text("Dr. Marco Hasselkuß")
		  .call(wrap, textWidth);		  
      }
      //slide 1
	  var slide_1 = d3.select("svg").append("g")
		.attr("id", "slide1");
	  slide_1.append("rect")
		.style("fill", "white")
		.attr("class", "slide")
		.attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_1.append("text")
		.attr("class", "slideText")
        .text("")
        .attr("x", center - 170)
        .attr("y", title_offset_top);
	  slide_1.append("text")
		.attr("class", "slideText")
		.attr("x", center - textWidth / 2)
		.attr("y", text_offset_top + title_offset_top + lineHeight)
		.text("") 
		.call(wrap, textWidth);
	  slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 4-50)
        .text("Im Folgenden werden Ihnen einige Fragen dazu gestellt, von welchen Personen an Ihrer Schule oder aus dem regionalen Schulnetzwerk Sie in den letzten drei Monaten Informationen zur Digitalisierung in der schulischen Arbeit.")
		.attr("class", "slideText")
		.call(wrap, textWidth);
	  slide_1.append("text")
		.attr("class", "underlinedSlideText")
		.text("erhalten")
		.attr("x", center - textWidth / 2.25)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 6-50)
		.call(wrap, textWidth);
	  slide_1.append("text")
		.attr("class", "slideText")
		.text("haben")
        .attr("x", center - textWidth / 2.7)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 6-50)
		.call(wrap, textWidth);
	  slide_1.style("display", "none");
		
      // Slide 3       
//schon angepasst: Namenseingabe Seite
      var slide_3 = d3.select("svg").append("g")
        .attr("id", "slide3");
      slide_3.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_3.append("rect")
		.attr("class", "borderBox")
		.attr("x", center - (textWidth / 2))
		.attr("y", 30)
		.attr("width", textWidth)
		.attr("height", 60);
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wenn Sie an die letzten drei Monate denken, von welchen Personen haben Sie Informationen über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien zum Thema Digitalisierung der Schule erhalten?")
        .call(wrap, textWidth);
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1))
        .text("Die Informationen können sich auf Fragen der technischen Ausstattung, digitaler Unterrichtsentwicklung, Fortbildungsangebote oder auch organisatorischer Rahmenbedingungen beziehen und können sowohl von Personen an Ihrer eigenen Schule (auch Schüler- sowie Elternvertreter*innen) als auch von Personen an anderen Schulen bzw. Organisationen im regionalen Schulnetzwerk zur Digitalisierung stammen. Es kann sich dabei auch um die Fortsetzung eines früheren Austauschs zu diesen Themen handeln.")
        .call(wrap, textWidth);
	  slide_3.append("text")
		.attr("class", "boldSlideText")
		.attr("id", "erläuterungen")
		.attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1) + ($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1) )
		.text("Erläuterungen")
		.call(wrap, textWidth);
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1) +($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1))
		.text("Bitte geben Sie keine vollständigen Namen an. Nutzen Sie stattdessen die ersten beiden Buchstaben des Vor- und Nachnamens (Herrmann Müller = HeMü). Nehmen Sie ggf. die Liste der Teilnehmer*innen des letzten Netzwerktreffens zur Hilfe. Sie können bis zu 5 Personen angeben. Wenn Sie zu dieser Frage keine Personen angeben möchten, klicken Sie bitte einfach 'Weiter'.")
        .call(wrap, textWidth);
      var textheight = $('#slide3 .slideText tspan').length + $('#slide3 .slideText').length;
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("") //Gibt es weitere Personen, mit denen Sie sich zum Thema Digitalisierung der Schule ausgetauscht haben? Bitte geben Sie die Abkürzung der Namen ein.
        .call(wrap, textWidth);
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Gibt es weitere Personen, mit denen Sie sich zum Thema Digitalisierung der Schule ausgetauscht haben? Bitte geben Sie die Abkürzung der Namen ein.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_3.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Danke, dass Sie diese Namen angegeben haben. Bitte klicken Sie \"Weiter\" um fortzufahren.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_3.style("display", "none");
              
      // Slide 4

      var slide_4 = d3.select("svg").append("g")
        .attr("id", "slide4");
      slide_4.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_4.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wir werden Ihnen nun einige Fragen zu diesen Personen stellen.")
        .call(wrap, textWidth);
      slide_4.style("display", "none");
      
	  // Slide 5
      var slide_5 = d3.select("svg").append("g")
        .attr("id", "slide5");
      slide_5.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_5.append("text")
        .attr("class", "slideText")// numfri1
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Welcher Gruppe gehören die von Ihnen genannten Personen an?")
        .call(wrap, textWidth);
      slide_5.append("text")
        .attr("class", "slideText")// numfri2
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide5 .slideText tspan').length + $('#slide5 .slideText').length-1))
        .text("Ziehen Sie die Kreise mit den Abkürzungen in die Box unten, die anzeigt, wo die Person angesiedelt ist.")
        .call(wrap, textWidth);
      slide_5.style("display", "none");
        
      // Slide 6

      var slide_6 = d3.select("svg").append("g")
        .attr("id", "slide6");
      slide_6.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_6.append("text")
        .attr("class", "slideText")//numfri1
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wie oft haben Sie mit den genannten Personen in den letzten drei Monaten über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien zur Digitalisierung gesprochen?")
        .call(wrap, textWidth);
      slide_6.append("text")
        .attr("class", "slideText") //numfri2
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide6 .slideText tspan').length + $('#slide6 .slideText').length-1))
        .text("Ziehen Sie die Kreise mit den Abkürzungen in die Box unten, die am besten anzeigt, wie häufig das war.")
        .call(wrap, textWidth);
      slide_6.style("display", "none");

      // Slide 7
      var slide_7 = d3.select("svg").append("g")
        .attr("id", "slide7")
        .style("display", "none")
      slide_7.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight)
      slide_7.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wir stellen Ihnen nun nacheinander Fragen zu jeder der von Ihnen genannten Personen.")	//Text entsprechend anpassen/entfernen!
        .call(wrap, textWidth)
	  slide_7.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide7 .slideText tspan').length + $('#slide7 .slideText').length-1))
        .text("Bitte beantworten Sie diese Fragen für jede Person. Bitte klicken Sie \"Weiter\" um fortzufahren.")	//Text entsprechend anpassen/entfernen!
        .call(wrap, textWidth);

	  //Slide 12
	  
    var slide_12 = d3.select("svg").append("g")
        .attr("id", "slide12");
      slide_12.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_12.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Soweit Sie wissen: Welche der von Ihnen genannten Personen tauschen sich auch untereinander über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien der schulischen Digitalisierung aus?") 
        .call(wrap, textWidth);
      slide_12.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide12 .slideText tspan').length + $('#slide12 .slideText').length-1))
        .text("Um anzugeben, dass sich zwei Personen untereinander austauschen, klicken Sie zunächst auf die erste Person und dann auf die zweite. Dadurch wird eine Linie zwischen beiden eingezeichnet. ")
        .call(wrap, textWidth);
      slide_12.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide12 .slideText tspan').length + $('#slide12 .slideText').length-1))
        .text("Bitte zeichnen Sie eine Linie zwischen allen Personen, die sich untereinander austauschen. Klicken Sie „weiter“, wenn Sie fertig sind. ")
        .call(wrap, textWidth);
      slide_12.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide12 .slideText tspan').length + $('#slide12 .slideText').length-1))
        .text("Wenn Sie versehentlich eine Linie gezeichnet haben, lässt sich diese mit einem Rechtsklick auf die Linie löschen.")
        .call(wrap, textWidth);
      slide_12.style("display", "none");
	  
	  //Slide 14
	  
	  var slide_14 = d3.select("svg").append("g")
		.attr("id", "slide14");
	  slide_14.append("rect")
		.style("fill", "white")
		.attr("class", "slide")
		.attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_14.append("text")
		.attr("class", "slideText")
        .text("")
        .attr("x", center - 170)
        .attr("y", title_offset_top);
	  slide_14.append("text")
		.attr("class", "slideText")
		.attr("x", center - textWidth / 2)
		.attr("y", text_offset_top + title_offset_top + lineHeight)
		.text("") 
		.call(wrap, textWidth);
	  slide_14.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 4-50)
          .text("Im Folgenden werden Ihnen nun einige Fragen dazu gestellt, an welche Personen an Ihrer Schule oder aus dem regionalen Schulnetzwerk Sie selbst in den letzten drei Monaten Informationen zur Digitalisierung in der schulischen Arbeit weitergegeben haben.")
          .call(wrap, textWidth);
	  slide_14.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 8-50)
          .text("Die Weitergabe von Informationen kann häufig auch als Bericht an eine Arbeitsgruppe oder ein Gremium erfolgen. Die letzte Frage dieses Teils bezieht sich auf eine solche formale Informationsweitergabe, nennen Sie hier bitte dennoch zentrale Personen des Gremiums. Bitte nennen Sie Personen auch dann, wenn Sie sie zuvor bereits bei der Frage nach erhaltenen Informationen angegeben haben. ")
          .call(wrap, textWidth);	  
	  slide_14.style("display", "none");
	  
	  //Slide 15
	var slide_15 = d3.select("svg").append("g")
        .attr("id", "slide15");
      slide_15.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_15.append("rect")
		.attr("class", "borderBox")
		.attr("x",center - (textWidth / 2))	//240
		.attr("y", 30)	//30
		.attr("width", textWidth)
		.attr("height", 60);
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wenn Sie an die letzten drei Monate denken, an wen haben Sie Informationen über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien zum Thema Digitalisierung der Schule weitergegeben?")
        .call(wrap, textWidth);
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide15 .slideText tspan').length + $('#slide15 .slideText').length-1))
        .text("Die Informationen können sich auf Fragen der technischen Ausstattung, digitaler Unterrichtsentwicklung, Fortbildungsangebote oder auch organisatorischer Rahmenbedingungen beziehen und sowohl an Personen an Ihrer eigenen Schule (auch Schüler- sowie Elternvertreter*innen) als auch an Personen an anderen Schulen bzw. Organisationen im regionalen Schulnetzwerk zur Digitalisierung weitergegeben worden sein. Es kann sich dabei auch um die Fortsetzung eines früheren Austauschs zu diesen Themen handeln.")
        .call(wrap, textWidth);
	  slide_15.append("text")
		.attr("class", "boldSlideText")
		.attr("id", "erläuterungen1")
		.attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide15 .slideText tspan').length + $('#slide15 .slideText').length-1) + ($('#slide15 .slideText tspan').length + $('#slide15 .slideText').length-1) )
		.text("Erläuterungen")
		.call(wrap, textWidth);
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide15 .slideText tspan').length + $('#slide15 .slideText').length-1) +($('#slide15 .slideText tspan').length + $('#slide15 .slideText').length-1))
		.text("Bitte geben Sie keine vollständigen Namen an. Nutzen Sie stattdessen die ersten beiden Buchstaben des Vor- und Nachnamens (Herrmann Müller = HeMü). Nehmen Sie ggf. die Liste der Teilnehmer*innen des letzten Netzwerktreffens oder ähnliches zur Hilfe. Sie können bis zu 5 Personen angeben. Wenn Sie zu dieser Frage keine Personen angeben möchten, klicken Sie bitte einfach 'Weiter'.")
        .call(wrap, textWidth);
      var textheight = $('#slide15 .slideText tspan').length + $('#slide15 .slideText').length;
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("")//Gibt es weitere Personen, mit denen Sie sich zum Thema Digitalisierung der Schule ausgetauscht haben? Bitte geben Sie die Abkürzung der Namen ein.
        .call(wrap, textWidth);
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Gibt es weitere Personen, denen Sie Informationen zum Thema Digitalisierung der Schule weitergegeben haben? Bitte geben Sie die Abkürzung der Namen ein. Falls nicht zutreffend, klicken Sie bitte weiter.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_15.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Danke, dass Sie diese Namen angegeben haben. Bitte klicken Sie \"Weiter\" um fortzufahren.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_15.style("display", "none");
	  
	  //Slide 33
	var slide_33 = d3.select("svg").append("g")
        .attr("id", "slide33");
	 slide_33.append("rect")
		.style("fill", "white")
		.attr("class", "slide")
		.attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_33.append("text")
		.attr("class", "slideText")
        .text("")
        .attr("x", center - 170)
        .attr("y", title_offset_top);
	  slide_33.append("text")
		.attr("class", "slideText")
		.attr("x", center - textWidth / 2)
		.attr("y", text_offset_top + title_offset_top + lineHeight)
		.text("") 
		.call(wrap, textWidth);
	  slide_33.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 4-50)
          .text("Im Folgenden werden Ihnen nun einige Fragen dazu gestellt, mit welchen Personen an Ihrer Schule oder aus dem regionalen Schulnetzwerk Sie in den letzten drei Monaten arbeitsteilig an Strategien oder Maßnahmen zur Digitalisierung in der Schule zusammengearbeitet haben. ")
          .call(wrap, textWidth);
	  slide_33.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 8-50)
          .text("Bitte nennen Sie Personen auch dann, wenn Sie sie zuvor bereits bei den Fragen nach Informationsaustauschen angegeben haben. ")
          .call(wrap, textWidth);	  
	  slide_33.style("display", "none");
	  
	  //Slide 34
	var slide_34 = d3.select("svg").append("g")
        .attr("id", "slide34");
      slide_34.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_34.append("rect")
		.attr("class", "borderBox")
		.attr("x", center - (textWidth / 2))
		.attr("y", 30)
		.attr("width", textWidth)
		.attr("height", 60);
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wenn Sie an die letzten drei Monate denken, mit welchen Personen haben Sie arbeitsteilig an bestehenden oder neuen Strategien oder Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet? ")
        .call(wrap, textWidth);
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide34 .slideText tspan').length + $('#slide34 .slideText').length-1))
        .text("Damit ist beispielsweise gemeint, dass Sie arbeitsteilig mit Personen dokumentiert haben, welche Fähigkeiten in Bezug auf digitale Medien Schüler*innen im jeweiligen Unterricht vermittelt wurden oder Sie mit Personen Absprachen darüber getroffen haben, unter welchen Rahmenbedingungen und mit welchen Mitteln eine digitale Notenverwaltung eingeführt werden kann. Die Zusammenarbeit kann sich dementsprechend auf Fragen der technischen Ausstattung, digitaler Unterrichtsentwicklung, Fortbildungsangebote oder auch organisatorischer Rahmenbedingungen beziehen und umfasst sowohl Personen an Ihrer eigenen Schule (auch Schüler- sowie Elternvertreter*innen) als auch Personen an anderen Schulen bzw. Organisationen im regionalen Schulnetzwerk zur Digitalisierung. ")
        .call(wrap, textWidth);
	  slide_34.append("text")
		.attr("class", "boldSlideText")
		.attr("id", "erläuterungen2")
		.attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide34 .slideText tspan').length + $('#slide34 .slideText').length-1) + ($('#slide34 .slideText tspan').length + $('#slide34 .slideText').length-1) )
		.text("Erläuterungen")
		.call(wrap, textWidth);
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide34 .slideText tspan').length + $('#slide34 .slideText').length-1) +($('#slide34 .slideText tspan').length + $('#slide34 .slideText').length-1))
		.text("Bitte geben Sie keine vollständigen Namen an. Nutzen Sie stattdessen die ersten beiden Buchstaben des Vor- und Nachnamens (Herrmann Müller = HeMü). Nehmen Sie ggf. die Liste der Teilnehmer*innen des letzten Netzwerktreffens zur Hilfe. Sie können bis zu 5 Personen angeben. Wenn Sie zu dieser Frage keine Personen angeben möchten, klicken Sie bitte einfach 'Weiter'.")
        .call(wrap, textWidth);
      var textheight = $('#slide34 .slideText tspan').length + $('#slide34 .slideText').length;
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("")
        .call(wrap, textWidth);
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Gibt es weitere Personen, mit denen Sie arbeitsteilig an Strategien und Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet haben? Bitte geben Sie die Abkürzung der Namen ein. Falls nicht zutreffend, klicken Sie bitte weiter.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_34.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Danke, dass Sie diese Namen angegeben haben. Bitte klicken Sie \"Weiter\" um fortzufahren.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_34.style("display", "none");
	  
	 //Slide 37 
	var slide_37 = d3.select("svg").append("g")
        .attr("id", "slide37");
      slide_37.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_37.append("text")
        .attr("class", "slideText")	//numfri1
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wie oft haben Sie mit den genannten Personen in den letzten drei Monaten arbeitsteilig an bestehenden oder neuen Maßnahmen oder Strategien zur Digitalisierung zusammengearbeitet?")
        .call(wrap, textWidth);
      slide_37.append("text")
        .attr("class", "slideText")//numfri2
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide37 .slideText tspan').length + $('#slide37 .slideText').length-1))
        .text("Ziehen Sie die Kreise mit den Abkürzungen in die Box unten, die am besten anzeigt, wie häufig das war.")
        .call(wrap, textWidth);
      slide_37.style("display", "none");
	  
	//Slide 50
	var slide_50 = d3.select("svg").append("g")
        .attr("id", "slide50");
      slide_50.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_50.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Soweit Sie wissen: Welche der von Ihnen genannten Personen haben auch untereinander arbeitsteilig an bestehenden oder neuen Strategien oder Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet?") 
        .call(wrap, textWidth);
      slide_50.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide50 .slideText tspan').length + $('#slide50 .slideText').length-1))
        .text("Um anzugeben, dass zwei Personen arbeitsteilig zusammengearbeitet haben, klicken Sie zunächst auf die erste Person und dann auf die zweite. Dadurch wird eine Linie zwischen beiden eingezeichnet. ")
        .call(wrap, textWidth);
      slide_50.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide50 .slideText tspan').length + $('#slide50 .slideText').length-1))
        .text("Bitte zeichnen Sie eine Linie zwischen allen Personen, die untereinander arbeitsteilig zusammengearbeitet haben. Klicken Sie „weiter“, wenn Sie fertig sind. ")
        .call(wrap, textWidth);
      slide_50.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide50 .slideText tspan').length + $('#slide50 .slideText').length-1))
        .text("Wenn Sie versehentlich eine Linie gezeichnet haben, lässt sich diese mit einem Rechtsklick auf die Linie löschen.")
        .call(wrap, textWidth);
      slide_50.style("display", "none");
	  
	  //Slide 52
	 var slide_52 = d3.select("svg").append("g")
        .attr("id", "slide52");
	 slide_52.append("rect")
		.style("fill", "white")
		.attr("class", "slide")
		.attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_52.append("text")
		.attr("class", "slideText")
        .text("")
        .attr("x", center - 170)
        .attr("y", title_offset_top);
	  slide_52.append("text")
		.attr("class", "slideText")
		.attr("x", center - textWidth / 2)
		.attr("y", text_offset_top + title_offset_top + lineHeight)
		.text("") 
		.call(wrap, textWidth);
	  slide_52.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 3-50)//*4
          .text("Im Folgenden werden Ihnen nun einige Fragen dazu gestellt, mit welchen Personen an Ihrer Schule oder aus dem regionalen Schulnetzwerk Sie sich in den letzten drei Monaten getroffen haben (auch virtuell/digital unterstützt oder telefonisch), um gemeinsam systematisch Strategien oder Maßnahmen zur Digitalisierung zu entwickeln. ")
          .call(wrap, textWidth);
	  slide_52.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 8-50)
          .text("Bitte nennen Sie Personen auch dann, wenn Sie sie zuvor bereits bei den Fragen nach Informationsaustauschen oder der Zusammenarbeit angegeben haben. ")
          .call(wrap, textWidth);	  
	  slide_52.style("display", "none");
	  
	  //Slide 53
	  	var slide_53 = d3.select("svg").append("g")
        .attr("id", "slide53");
      slide_53.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_53.append("rect")
		.attr("class", "borderBox")
		.attr("x", center - (textWidth / 2))
		.attr("y", 30)
		.attr("width", textWidth)
		.attr("height", 60);
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wenn Sie an die letzten drei Monate denken, mit welchen Personen haben Sie sich getroffen, um gemeinsam systematisch Strategien oder Maßnahmen zur Digitalisierung an der Schule zu entwickeln?")
        .call(wrap, textWidth);
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1))
        .text("Damit ist beispielsweise gemeint, dass Sie sich mit Personen getroffen haben, um systematisch Unterrichtsstunden, in denen digitale Medien eingesetzt werden, zu entwickeln, gegenseitige Unterrichtshospitationen mit einer Person durchgeführt haben oder sich mit Personen getroffen haben, um systematisch die technische Ausstattung (z.B. Soft- und Hardware) der Schule weiterzuentwickeln. ")
        .call(wrap, textWidth);
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1))
        .text("Die gemeinsame Entwicklung kann sich dementsprechend auf Fragen der technischen Ausstattung, digitaler Unterrichtsentwicklung, Fortbildungsangebote oder auch organisatorischer Rahmenbedingungen beziehen und sowohl Personen an Ihrer eigenen Schule (auch Schüler- sowie Elternvertreter*innen) als auch Personen an anderen Schulen bzw. Organisationen im regionalen Schulnetzwerk zur Digitalisierung umfassen.")
        .call(wrap, textWidth);
	  slide_53.append("text")
		.attr("class", "boldSlideText")
		.attr("id", "erläuterungen3")
		.attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1) + ($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1) )
		.text("Erläuterungen")
		.call(wrap, textWidth);
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time3")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1) +($('#slide53 .slideText tspan').length + $('#slide53 .slideText').length-1))
		.text("Bitte geben Sie keine vollständigen Namen an. Nutzen Sie stattdessen die ersten beiden Buchstaben des Vor- und Nachnamens (Herrmann Müller = HeMü). Nehmen Sie ggf. die Liste der Teilnehmer*innen des letzten Netzwerktreffens zur Hilfe. Sie können bis zu 5 Personen angeben. Wenn Sie zu dieser Frage keine Personen angeben möchten, klicken Sie bitte einfach 'Weiter'.")
        .call(wrap, textWidth);
      var textheight = $('#slide53 .slideText tspan').length + $('#slide53 .slideText').length;
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text3")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("")//Gibt es weitere Personen, mit denen Sie arbeitsteilig an Strategien und Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet haben? Bitte geben Sie die Abkürzung der Namen ein. Falls nicht zutreffend, klicken Sie bitte weiter. 
        .call(wrap, textWidth);
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text3")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Gibt es weitere Personen, mit denen Sie arbeitsteilig an Strategien und Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet haben? Bitte geben Sie die Abkürzung der Namen ein. Falls nicht zutreffend, klicken Sie bitte weiter.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_53.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text3")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Danke, dass Sie diese Namen angegeben haben. Bitte klicken Sie \"Weiter\" um fortzufahren.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_53.style("display", "none");
	  
	  //slide 57
	 var slide_57 = d3.select("svg").append("g")
        .attr("id", "slide57");
      slide_57.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_57.append("text")
        .attr("class", "slideText")	//numfri1
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Wie oft haben Sie mit den genannten Personen in den letzten drei Monaten gemeinsam, systematisch Maßnahmen oder Strategien zur Digitalisierung entwickelt?")
        .call(wrap, textWidth);
      slide_57.append("text")
        .attr("class", "slideText")//numfri2
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide57 .slideText tspan').length + $('#slide57 .slideText').length-1))
        .text("Ziehen Sie die Kreise mit den Abkürzungen in die Box unten, die am besten anzeigt, wie häufig das war.")
        .call(wrap, textWidth);
      slide_57.style("display", "none");
	  
	  //slide 69
	 var slide_69 = d3.select("svg").append("g")
        .attr("id", "slide69");
      slide_69.append("rect") 
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_69.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Soweit Sie wissen: Welche der von Ihnen genannten Personen haben sich auch untereinander getroffen, um gemeinsam systematisch Strategien und Maßnahmen zur Digitalisierung an der Schule zu entwickeln?") 
        .call(wrap, textWidth);
      slide_69.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide69 .slideText tspan').length + $('#slide69 .slideText').length-1))
        .text("Um anzugeben, dass zwei Personen sich getroffen haben, um gemeinsam systematisch Strategien und Maßnahmen zu entwickeln, klicken Sie zunächst auf die erste Person und dann auf die zweite. Dadurch wird eine Linie zwischen beiden eingezeichnet. ")
        .call(wrap, textWidth);
      slide_69.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide69 .slideText tspan').length + $('#slide69 .slideText').length-1))
        .text("Bitte zeichnen Sie eine Linie zwischen allen Personen, die sich untereinander getroffen haben, um gemeinsam Strategien oder Maßnahmen zu entwickeln. Klicken Sie „weiter“, wenn Sie fertig sind. ")
        .call(wrap, textWidth);
      slide_69.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide69 .slideText tspan').length + $('#slide69 .slideText').length-1))
        .text("Wenn Sie versehentlich eine Linie gezeichnet haben, lässt sich diese mit einem Rechtsklick auf die Linie löschen.")
        .call(wrap, textWidth);
      slide_69.style("display", "none");

	//slide 71  
	var slide_71 = d3.select("svg").append("g")
		.attr("id", "slide71");
	  slide_71.append("rect")
		.style("fill", "white")
		.attr("class", "slide")
		.attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
	  slide_71.append("text")
		.attr("class", "slideText")
        .text("")
        .attr("x", center - 170)
        .attr("y", title_offset_top);
	  slide_71.append("text")
			.attr("class", "slideText")
			.attr("x", center - textWidth / 2)
			.attr("y", text_offset_top + title_offset_top + lineHeight)
			.text("") 
			.call(wrap, textWidth);
	  slide_71.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 4)
          .text("Herzlichen Dank für Ihre Mühe!")
          .call(wrap, textWidth);
	  slide_71.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 5)
          .text("Bitte klicken Sie auf 'Senden' um Ihre Eingabe zu übermitteln. Anschließend können Sie das Fenster schließen.") //Ihre Eingaben wurden gespeichert und Sie können das Fenster jetzt schließen.
          .call(wrap, textWidth);
	  slide_71.style("display", "none");
	
	  
	//----------------------------------------------------------------------------------        
	// Boxes indicating frequency into which nodes are dragged (4, 5, 6 or 8 categories)
	//----------------------------------------------------------------------------------

      var fourBar = d3.select("svg").append("g")
        .attr("id", "fourBar")
        .style("display", "none");

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "several")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "daily")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar4_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "multiple")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar4_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "weekly")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar4_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      var fiveBar = d3.select("svg").append("g")
        .attr("id", "fiveBar")
        .style("display", "none")

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "one")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "one_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width / 2) - 28)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "two")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "two_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height); 

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin + (bar_target_width / 2) - 25)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "three")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "three_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2 + (bar_target_width / 2) - 23)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "four")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "four_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3 + (bar_target_width / 2) - 20)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "five")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "five_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("") 
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4 + (bar_target_width / 2) - 15)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      var sixBar = d3.select("svg").append("g")
        .attr("id", "sixBar")
        .style("display", "none")

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "one")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "one_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "two")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "two_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height); 

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "three")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "three_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "four")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "four_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "five")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "five_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "six")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "six_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

	
	var tenBar = d3.select("svg").append("g")
        .attr("id", "tenBar")
        .style("display", "none")
	 tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "one")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - 150) //linke boxbar um 130 nach oben versetzt - über die zweite Box
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "one_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);
		
	  tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "two")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "two_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

	  tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "three")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 1)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "three_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 1)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);
 
      tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "four")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "four_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);
	 
      tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "five")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "five_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);
	 
      tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "six")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "six_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);	

      tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "seven")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "seven_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "eight")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - 300)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "eight_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin -300)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);	
      
	  tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "nine")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - 450)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "nine_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin -450)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);	

	  tenBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "ten")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - 150)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      tenBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "ten_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin -150)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);			

	//------------------------------------------
	// Labels and Texts for Drag-and-Drop Bars
	//------------------------------------------
 
//labels and texts for sixBar 1
   var sixLabelBar1 = d3.select("svg").append("g")
        .style("display", "none")
        .attr("id", "labelBar1");
        
     sixLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "gar_nicht")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Gar Nicht")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 40) //an diesen Stellen jeweils etwas verändern um die Labels anzupassen!
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17); //-6

      sixLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "einmal")     
        .attr("rx", 4) 
        .attr("ry", 4) 
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height); 

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Einmal")
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin + (bar6_target_width / 2) - 30)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);

      sixLabelBar1.append("rect")
          .attr("class", "bar_label")
          .attr("id", "zweimal")     
          .attr("rx", 4)
          .attr("ry", 4)
          .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
          .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
          .attr("width", bar6_target_width)
          .attr("height", bar_label_height);

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Zweimal")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + (bar6_target_width / 2) - 40)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);
        
      sixLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "einmal_im_monat")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Einmal im Monat")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + (bar6_target_width / 2) - 70)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);
        
      sixLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "einmal_in_der_woche")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Einmal in der Woche")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + (bar6_target_width / 2) - 80 )
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);
		
	  sixLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "mehrmals_pro_woche")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Mehr als einmal pro Woche")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + (bar6_target_width / 2) - 90)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);
		
//labels and texts for tenBar 1
   var eightLabelBar1 = d3.select("svg").append("g")
        .style("display", "none")
        .attr("id", "labelBar2");
     
	  eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "sonstiges")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin -150)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Sonstiges")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + (bar6_target_width / 2) - 50)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 16 - 150);

      eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "mitglied_meiner_schule")     
        .attr("rx", 4) 
        .attr("ry", 4) 
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin - 450)       
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height); 

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Mitglied meiner Schule")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 80)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 16 - 450);

      eightLabelBar1.append("rect")
          .attr("class", "bar_label")
          .attr("id", "mitglied_anderer_netzwerkschule")     
          .attr("rx", 4)
          .attr("ry", 4)
          .attr("x", boxbar_offset_x)
          .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin - 300)          
 		  .attr("width", bar6_target_width)
          .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Mitglied anderer")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 50)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 26 - 300);
		//append text for second line
	  eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Netzwerkschule")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 50)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 26 - 280);	
        
      eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "schülerIn")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Schüler*in")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 30) //an diesen Stellen jeweils etwas verändern um die Labels anzupassen!!!
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17 - 150);      
		
      eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "externe_medienberatung")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x) //+bar6_target_width + boxbar_margin
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Externe Medienberatung")
		.attr("x", boxbar_offset_x + (bar6_target_width / 2) - 80) //bar6_target_width + boxbar_margin + 
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);

	  eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "schulträger")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 1)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Schulträger")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 1 + (bar6_target_width / 2) - 50)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);

      eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "learning_lab")     
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Learning Lab der")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + (bar6_target_width / 2)-100)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 20);
		//append text for second line
	  eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Universität Duisburg-Essen")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + (bar6_target_width / 2)-100)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 5);
		
	  eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "externe_fortbildungsakteure")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
		.text("Externe Fortbildungsakteure/")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + (bar6_target_width / 2)-100)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 20);
		//append text for second line
	  eightLabelBar1.append("text")
        .attr("class", "bar_text")
		.text("Kompetenzteams NRW")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + (bar6_target_width / 2)-100)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 5);
		
	  eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "bezirksregierung")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Bezirksregierung")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + (bar6_target_width / 2) - 50 )
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);

      eightLabelBar1.append("rect")
        .attr("class", "bar_label")
        .attr("id", "elternvertreter")     
        .attr("rx", 4)
        .attr("ry", 4)
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      eightLabelBar1.append("text")
        .attr("class", "bar_text")
        .text("Elternvertreter")
		.attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + (bar6_target_width / 2) - 50)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 17);		

      //---------------------------------------------
      // Declaration of functions for nodes and links
      //---------------------------------------------

      // Graph iteration
      function tick() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; })
            .attr("name", function(d) { return d.name; })
            .attr("id", function(d) { return d.id; })
            .attr("race", function(d) { return d.race; })
            .attr("edu", function(d) { return d.edu; })
            .attr("freq", function(d) { return d.freq; })
            .attr("gender", function(d) { return d.gender; })
            .attr("transform", function(d){return "translate("+d.x+","+d.y+")"});
      }

      // Add node to graph
      function addFriend() {
        var friendName = document.getElementById("friendNameID");

        if (friendName.value.length > 4 || friendName.value.indexOf(' ') != -1) {
          promptOnlyOne();

        } else if (friendName.value.length > 0) {

          if (numFriends == 0) {
            //document.getElementById("final_friend_text").style.display = "none";
            document.getElementById("first_friend_text").style.display = "none";
            document.getElementById("second_friend_text").style.display = "block";
			
			document.getElementById("first_friend_text1").style.display = "none";
            document.getElementById("second_friend_text1").style.display = "block";
			
			document.getElementById("first_friend_text2").style.display = "none";
            document.getElementById("second_friend_text2").style.display = "block";
			
			document.getElementById("first_friend_text3").style.display = "none";
            document.getElementById("second_friend_text3").style.display = "block";
          }

          if (numFriends == 4) {
			//vlt. zusätzlich den restlichen Fragentext ausblenden und nurnoch die rote Schrift anzeigen lassen ?
			//TODO: Das mit dem Erläuterung ausblenden noch für alle Durchgänge anpassen!
			document.getElementById("second_friend_text").style.display = "none";
            document.getElementById("final_friend_text").style.display = "block";
            document.getElementById("erläuterungen").style.display = "none";
			document.getElementById("one_at_a_time").style.display = "none";
			
			document.getElementById("second_friend_text1").style.display = "none";
            document.getElementById("final_friend_text1").style.display = "block";
            document.getElementById("erläuterungen1").style.display = "none";
			document.getElementById("one_at_a_time1").style.display = "none";
			
			document.getElementById("second_friend_text2").style.display = "none";
            document.getElementById("final_friend_text2").style.display = "block";
            document.getElementById("erläuterungen2").style.display = "none";
			document.getElementById("one_at_a_time2").style.display = "none";
			
			document.getElementById("second_friend_text3").style.display = "none";
            document.getElementById("final_friend_text3").style.display = "block";
            document.getElementById("erläuterungen3").style.display = "none";
			document.getElementById("one_at_a_time3").style.display = "none";
		
            document.getElementById("name_input").style.display = "none";
          }

          numFriends++;
		  
		  //hides last text for further cycle
		  //TODO: funktioniert so nicht, so werden die final_friend texte garnicht erst angezeigt
		  document.getElementById("final_friend_text").style.display = "none";
          document.getElementById("final_friend_text1").style.display = "none";
		  document.getElementById("final_friend_text2").style.display = "none";
		  document.getElementById("final_friend_text3").style.display = "none";
		  
          if (numFriends <= 5) {
            var node = {name: friendName.value, 
                        id: numFriends, 
                        gender:"", 
                        age:0,
                        race:"",
                        religion:"", 
                        surveyTime:0,
                        sawStats:false,
                        edu:null, 
                        freq:null,
                        friendsWith:"",
                        haveBlackFriends:"",
                        friendsBlackFriends:"",
                        artDerKommunikation:"",
                        seeBlackFriends:"",
                        enjoy:"",
                        like:"",
                        interest:"",
                        motivation:""}
            n = nodes.push(node);
            
            links.push({source: node, target: 0});

            restart();
          }

          document.getElementById("friendNameID").value = '';
        }
      }
      
      // Whenever nodes or links are added or changes are made to their properties, the graph needs to be restarted
      function restart() {
        force.start();

        link = link.data(links);

        link.enter().insert("line", ".node")
            .attr("class", "link")
            .on("contextmenu", removeLink);
            
        link.exit().remove();

        node = node.data(nodes);

        var n = node.enter().append("svg:g")
          .attr("class", "node")
          .call(force.drag);
          
        n.append("svg:circle")
          .attr("class", "node")
          .attr("r", 25)
          .on("click", nodeSelect)
          .call(force.drag);
          
        n.append("svg:text")
          .attr("class", "node_text")
          .attr("text-anchor", "middle")  //middle
          .attr("dy", ".3em")
          .attr("pointer-events", "none")
          .text(function(d) { return d.name });

        n.attr("transform", function(d){return "translate("+d.x+","+d.y+")"});
      }
      
      // Remove link between two nodes
      function removeLink(l) {
        // Slide 13: draw links between friends that know each other 
		//Slide muss hier auf eine höher eingestellt werden als die CurrSlide der Frage an sich, sonst können Linien nicht (auf Slide 13) gezeichnet werden
        if (currSlide == 14) {
          links.splice(links.indexOf(l), 1);
          restart();
        } else if (currSlide == 32) {
		  links.splice(links.indexOf(l), 1);
		  restart();
		}else if (currSlide == 51) {
		  links.splice(links.indexOf(l), 1);
		  restart();
		}else if (currSlide == 70) {
		  links.splice(links.indexOf(l), 1);
		  restart();
		}
		
      }

      var selected = false;
      var targetId;
      var sourceId;

      // Handles node selections depending on the current slide
      function nodeSelect(d) {

        // Slide 13: draw links between friends that know each other
		//Slide muss hier auf eine höher eingestellt werden als die CurrSlide der Frage an sich, sonst können Linien nicht (auf Slide 13) gezeichnet werden
        if (currSlide == 14) {
          var targetIndex;
          var sourceIndex;

          if (selected == false) {
            targetId = d.id;
            console.log("targetId: " + targetId);
            selected = true;
          } else {
            sourceId = d.id;
            console.log("sourceid: " + sourceId);
            if (targetId != sourceId) {
              nodes.forEach(function(n) {
                if (n.id == targetId) {
                  targetIndex = n.index;
                  console.log("target: " + targetIndex);
                } else if (n.id == sourceId) {
                  sourceIndex = n.index;
                  console.log("source: " + sourceIndex);
                } 
              });
			  //Ab hier "friends with" Beziehungen bzw. links
              /*nodes[sourceIndex].friendsWith += targetIndex.toString()+",";
              nodes[targetIndex].friendsWith += sourceIndex.toString()+",";
              links.push({source: sourceIndex, target: targetIndex});
			  */
			  
			  nodes[sourceIndex].friendsWith += nodes[targetIndex].name.toString()+",";
              nodes[targetIndex].friendsWith += nodes[sourceIndex].name.toString()+",";
              links.push({source: sourceIndex, target: targetIndex});
            }
            selected = false;
          }
          restart();
		  
        }else if(currSlide == 32){
		  var targetIndex;
          var sourceIndex;

          if (selected == false) {
            targetId = d.id;
            console.log("targetId: " + targetId);
            selected = true;
          } else {
            sourceId = d.id;
            console.log("sourceid: " + sourceId);
            if (targetId != sourceId) {
              nodes.forEach(function(n) {
                if (n.id == targetId) {
                  targetIndex = n.index;
                  console.log("target: " + targetIndex);
                } else if (n.id == sourceId) {
                  sourceIndex = n.index;
                  console.log("source: " + sourceIndex);
                } 
              });
			  //Ab hier "friends with" Beziehungen bzw. links
			  nodes[sourceIndex].friendsWith += nodes[targetIndex].name.toString()+",";
              nodes[targetIndex].friendsWith += nodes[sourceIndex].name.toString()+",";
              links.push({source: sourceIndex, target: targetIndex});
            }
            selected = false;
          }
          restart();
		  
		}else if(currSlide == 51){
		  var targetIndex;
          var sourceIndex;

          if (selected == false) {
            targetId = d.id;
            console.log("targetId: " + targetId);
            selected = true;
          } else {
            sourceId = d.id;
            console.log("sourceid: " + sourceId);
            if (targetId != sourceId) {
              nodes.forEach(function(n) {
                if (n.id == targetId) {
                  targetIndex = n.index;
                  console.log("target: " + targetIndex);
                } else if (n.id == sourceId) {
                  sourceIndex = n.index;
                  console.log("source: " + sourceIndex);
                } 
              });
			  //Ab hier "friends with" Beziehungen bzw. links
			  nodes[sourceIndex].friendsWith += nodes[targetIndex].name.toString()+",";
              nodes[targetIndex].friendsWith += nodes[sourceIndex].name.toString()+",";
              links.push({source: sourceIndex, target: targetIndex});
            }
            selected = false;
          }
          restart();
		} else if(currSlide == 70){
		  var targetIndex;
          var sourceIndex;

          if (selected == false) {
            targetId = d.id;
            console.log("targetId: " + targetId);
            selected = true;
          } else {
            sourceId = d.id;
            console.log("sourceid: " + sourceId);
            if (targetId != sourceId) {
              nodes.forEach(function(n) {
                if (n.id == targetId) {
                  targetIndex = n.index;
                  console.log("target: " + targetIndex);
                } else if (n.id == sourceId) {
                  sourceIndex = n.index;
                  console.log("source: " + sourceIndex);
                } 
              });
			  //Ab hier "friends with" Beziehungen bzw. links
			  nodes[sourceIndex].friendsWith += nodes[targetIndex].name.toString()+",";
              nodes[targetIndex].friendsWith += nodes[sourceIndex].name.toString()+",";
              links.push({source: sourceIndex, target: targetIndex});
            }
            selected = false;
          }
          restart();
		}
      }

      // Makes all nodes default color
      function clearColors() {
        d3.selectAll(".node").style("fill", nodeColor)
      }
      
      //-------------------------------------------------------------------------
      // Declaration of functions for manipulating text, boxes and other elements
      //-------------------------------------------------------------------------
	  //wrapper Methode 
      // Wraps text to fit in a span of width 'width'
	  // Wraps text for Fullscreen-Slides
      function wrap(text, width) {
        text.each(function() {
          var text = d3.select(this),
              words = text.text().split(/\s+/).reverse(),
              word,
              line = [],
              lineNumber = 0,
              lineHeight = 1.1, // ems
              y = text.attr("y"),
              x = text.attr("x")
              dy = parseFloat(text.attr("dy")),
              tspan = text.text(null).append("tspan").attr("x", x).attr("y", y);
          while (word = words.pop()) {
            line.push(word);
            tspan.text(line.join(" "));
            if (tspan.node().getComputedTextLength() > width) {
              line.pop();
              tspan.text(line.join(" "));
              line = [word];
              tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", ++lineNumber * lineHeight + "em").text(word);       // ++lineNumber
            }
          }
        });
      }
	  
	  //wrapper Methode 
      // Wraps text to fit in a span of width 'width'
	  // Wraps text for Pop-Up Boxes for individual questions
	  function wrap2(text, width) {
        text.each(function() {
          var text = d3.select(this),
              words = text.text().split(/\s+/).reverse(),
              word,
              line = [],
              lineNumber = 0,
              lineHeight = 1.1, // ems
              y = text.attr("y"),
              x = text.attr("x")
              dy = parseFloat(text.attr("dy")),
              tspan = text.text(null).append("tspan").attr("x", x).attr("y", y);
          while (word = words.pop()) {
            line.push(word);
            tspan.text(line.join(" "));
            if (tspan.node().getComputedTextLength() > width) {
              line.pop();
              tspan.text(line.join(" "));
              line = [word];
              tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", 1 * lineHeight + "em").text(word);       // ++lineNumber
            }
          }
        });
      }
      
	  // Refreshes all radiobuttons, checkboxes and text input fields before going on
      function refreshRadio() {
		  
        var artKomm = document.getElementById("artDerKommunikation");
		var lernmöglichkeiten = document.getElementById("subjWahrLernm");
		var informationen = document.getElementById("erhInfo");
		var schulentwicklung = document.getElementById("digitaleSchulentwicklung");
		var funktionen = document.getElementById("funktionenDerPersonen");
		var formalisierung_1 = document.getElementById("gradDerFormalisierung");
		var formalisierung_2 = document.getElementById("gradDerFormalisierung2");
		var formalisierung_3 = document.getElementById("gradDerFormalisierung3");
	

        for(var i=0;i<artKomm.length;i++) {
          artKomm[i].checked = false;
        }
		for(var i=0;i<lernmöglichkeiten.length;i++) {
          lernmöglichkeiten[i].checked = false;
        }
		for(var i=0;i<informationen.length;i++) {
          informationen[i].checked = false;
        }
		for(var i=0;i<schulentwicklung.length;i++) {
          schulentwicklung[i].checked = false;
        }
		for(var i=0;i<funktionen.length;i++) {
          funktionen[i].checked = false;
        }
		for(var i=0;i<formalisierung_1.length;i++) {
          formalisierung_1[i].checked = false;
        }
		for(var i=0;i<formalisierung_2.length;i++) {
          formalisierung_2[i].checked = false;
        }
		for(var i=0;i<formalisierung_3.length;i++) {
          formalisierung_3[i].checked = false;
        }
		
		erhInfoInput.value = null;
		inputFunktionen.value = null;
      }

	function skipQuestions() {
		var artKomm = document.getElementById("artDerKommunikation");
		var lernmöglichkeiten = document.getElementById("subjWahrLernm");
		var informationen = document.getElementById("erhInfo");
		var schulentwicklung = document.getElementById("digitaleSchulentwicklung");
		var funktionen = document.getElementById("funktionenDerPersonen");
		
		//hides red second_friend_text of the addFriend given fewer than 5 friends
		document.getElementById("second_friend_text").style.display = "none";
		document.getElementById("second_friend_text1").style.display = "none";
		document.getElementById("second_friend_text2").style.display = "none";
		document.getElementById("second_friend_text3").style.display = "none";
		
		skipped = true;
		refreshRadio();
		restart();
		showNext();
	}

      // If respondent has not filled in an answer, reminds them
      function promptNonresponse() {
        document.getElementById("nonresponse_box").style.display = "block";
        document.getElementById("popup").style.display = "block";
      }
	  
      function promptNoId() {
        document.getElementById("noID_box").style.display = "block";
        document.getElementById("noIdPopup").style.display = "block";
      }

      function promptOnlyOne() {
        document.getElementById("onlyone_box").style.display = "block";
        document.getElementById("onlyOnePopup").style.display = "block";
      }

      function friendPromptNonresponse() {
        document.getElementById("fewFriends_box").style.display = "block";
        document.getElementById("friendPopup").style.display = "block";
      }

      function dragPromptNonresponse() {
        document.getElementById("fewDragged_box").style.display = "block";
        document.getElementById("dragPopup").style.display = "block";
      }

      function closePopup() {
        document.getElementById("nonresponse_box").style.display = "none";
        document.getElementById("popup").style.display = "none";
      }
	  function closeNoIDPopup() {
        document.getElementById("noID_box").style.display = "none";
        document.getElementById("noIdPopup").style.display = "none";
      }

      function closeOnlyOnePopup() {
        document.getElementById("onlyone_box").style.display = "none";
        document.getElementById("popup").style.display = "none";
      }

      function closeFriendPopup() {
        document.getElementById("fewFriends_box").style.display = "none";
        document.getElementById("friendPopup").style.display = "none";
      }

      function closeDragPopup() {
        document.getElementById("fewDragged_box").style.display = "none";
        document.getElementById("dragPopup").style.display = "none";
      }
	  
	  function saveValue() {
		 teilnehmercodeInput = document.getElementById("teilnehmercodeInput");
		 if (teilnehmercodeInput.value===null || teilnehmercodeInput.value===""){
			promptNoId();
		 }else{
			 document.getElementById("teilnehmercode").style.display = "none";
			 console.log("Teilnehmercode: " + teilnehmercodeInput.value);
		 }
	  }

      // Questions about individuals in the network
      function drawBox(node) {
        var q_x = (node.x - 142) * 0.071;
        var x = q_x.toString();

        var q_y = (node.y - 20) * 0.0725; //175 140
        var y = q_y.toString();
	
	//Original:
		artKommunikation = document.getElementById("artKommunikation");
		themaErhalteneInfos = document.getElementById("themaErhalteneInfos");
		digiSchulentwicklung = document.getElementById("digiSchulentwicklung");
		funktionenPersonen = document.getElementById("funktionenPersonen");
		wahrgenLernmöglichkeiten = document.getElementById("wahrgenLernmöglichkeiten");	
	

		themaErhalteneInfos.style.top = y + "em";
        themaErhalteneInfos.style.left = x + "em";
		
        artKommunikation.style.top = y + "em";
        artKommunikation.style.left = x + "em";
		
		digiSchulentwicklung.style.top = y + "em";
		digiSchulentwicklung.style.left = x + "em";
		
		funktionenPersonen.style.top = y + "em";
        funktionenPersonen.style.left = x + "em";
		
		wahrgenLernmöglichkeiten.style.top = y + "em";
        wahrgenLernmöglichkeiten.style.left = x + "em";
		
		//loading text inputs for further usage
		erhInfoInput = document.getElementById("myText");
		inputFunktionen = document.getElementById("weitereFunktionen");
		
		currSlide += .1;
		
      }

      
      // ---------------------------------------------------------------------------------------
      // showNext(): Prepares for next slide in survey. Hides previous slide and shows currSlide,
      // performing whatever operations needed for preparing slide.
      // A bit like the main() function
      // ---------------------------------------------------------------------------------------
	
	//Ab hier wird die Abfolge der Seiten geregelt
      function showNext() {
        if (currSlide == 0){
				currSlide++;
				showNext();
		
		}else if (currSlide == 1) {
          var d = new Date();
          startTime = d.getTime();
         
		 if (teilnehmercodeInput===null || teilnehmercodeInput.value===""){
		 promptNoId();
		 }else{
		 
		/*if (teilnehmercodeInput.value === null || teilnehmercodeInput.value === "") {
			promptNonresponse();
		}else {*/
		  //nach getElementById("next") wird die vorherige Seite verborgen und die neue Seite angezeigt, Seitenzahl wird hochgezählt
          document.getElementById("Next").style.position="absolute";
          document.getElementById("slide0").style.display = "none";
		  document.getElementById("teilnehmercode").style.display = "none";
		  document.getElementById("slide1").style.display = "block";
		  currSlide++;
		}
        }else if (currSlide ==2){
		  document.getElementById("Next").style.position="absolute";
          document.getElementById("slide1").style.display = "block";
		  currSlide++;
		  showNext();

//---------------------------1. Durchgang-------------------------------------------------------	
	  
        } else if (currSlide == 3) {
			document.getElementById("slide1").style.display = "none";
          d3.selectAll(".node").attr("display", "block");
          d3.selectAll(".node").on('mousedown.drag', function(d) {
            return d.index > 0 ? true : null;
          });
  
          // Q3: The following questions are about people with whom you discuss important matters 
                      
          document.getElementById("slide3").style.display = "block";
          document.getElementById("name_input").style.display = "block";
          document.getElementById("name_input").style.left = string_l + "px";

          currSlide++;
        } 
		else if (currSlide == 4) {
          if (numFriends < 5 && checked == false) {
            checked = true;
            console.log("fewer than 5 friends")
            friendPromptNonresponse();
			
		  } else if (numFriends == 0 && checked == true){
			//Skipps to the next question about Persons
			currSlide += 10;
			document.getElementById("slide3").style.display = "none";
			document.getElementById("name_input").style.display = "none";
			showNext();
			
          
		  } else {
			//Collect Data  
			for(var i = 1; i<= numFriends; i++){
				p1_name[i] = nodes[i].name;
				console.log("name" +i+ ": " + p1_name[i]);
			}
			
			for(var i = 1; i<= numFriends; i++){
				nodes1[i] = nodes[i];
			}
			
			
            checked = false;
            document.getElementById("slide3").style.display = "none";
            document.getElementById("slide4").style.display = "block";
            var text = $("#slide4 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide4 .numfri").text(text);
            
            document.getElementById("name_input").style.display = "none";
            currSlide++;
          }
        } 
	
//	Slides with tenBar

		else if (currSlide == 5){
		  document.getElementById("slide4").style.disply = "none";
		  
		  //prepare nodes for dragging into boxes

          d3.selectAll(".node").style("display", "block");
          clearColors();
          node[0].y -= 100;
          restart();
		  
          // Q2: Welcher Gruppe gehören die von Ihnen genannten Personen an?
		  document.getElementById("slide5").style.display = "block";
		  document.getElementById("tenBar").style.display = "block";
		  document.getElementById("labelBar2").style.display = "block";
		  
          var text = $("#slide5 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide5 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide5 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000); 

          currSlide++;
		}else if (currSlide == 6) {
          var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes - shows NonResponse message if Nodes are not correctly placed
		  nodes.forEach(function(n) {
            if (n.index > 0) {
			
			//old statement for 6 Boxes          
			/* if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }*/
			  
			//actual statement for ten Boxes   
              if (((n.x > boxbar_offset_x + bar6_target_width) && (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)) && (n.y < boxbar_offset_y)) || (n.y < (boxbar_offset_y - 450))){
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }
	
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });
		  
          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;
            nodes.forEach(function(n) {
              //TODO: abweichende Positionierung der 1. und 8. Box, boxbar_offset_y etc. entsprechend anpassen!
			  
			  //Collect Data
			  if (n.index > 0) {
				if (n.x < boxbar_offset_x + bar6_target_width && n.y< (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*2) && (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*3)) {
                  p1_dd1[i] = n.dd1 = "Mitglied meiner Schule"; 
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height) && n.y > (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height*2)) {
                  p1_dd1[i] = n.dd1 = "Mitglied anderer Netzwerkschule";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p1_dd1[i] = n.dd1 = "SchülerIn";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p1_dd1[i] = n.dd1 = "externe Medienberatung";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y){
                  p1_dd1[i] = n.dd1 = "Schulträger";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y){
                  p1_dd1[i] = n.dd1 = "externe Fortbildungsakteure";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y){
                  p1_dd1[i] = n.dd1 = "Learning Lab";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p1_dd1[i] = n.dd1 = "Elternvertreter";
				} else if (n.y > boxbar_offset_y){
                  p1_dd1[i] = n.dd1 = "Bezirksregierung";
                } else if (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + bar6_target_width) && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p1_dd1[i] = n.dd1 = "Sonstiges";
                }
				i++;
			}
            }); 

            checked = false;
 
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
          
            document.getElementById("labelBar2").style.display = "none";
            document.getElementById("tenBar").style.display = "none";  	
            document.getElementById("slide5").style.display = "none";  
			currSlide++;
			showNext();
          }
        }
		
//	Slides with sixBar

		else if (currSlide == 7) {
         document.getElementById("slide4").style.display = "none";

          // Prepare nodes for dragging into boxes
          d3.selectAll(".node").style("display", "block");
          clearColors();
          node[0].y -= 100;
          restart();
        
          // Q4: Welcher Gruppe gehören die von Ihnen genannten Personen an?
			
          document.getElementById("slide6").style.display = "block";		
          document.getElementById("sixBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";
          
          var text = $("#slide6 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide6 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide6 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000);

          currSlide++;
        } else if (currSlide == 8) {
          var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes
          nodes.forEach(function(n) {
            if (n.index > 0) {
              if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              } 
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
			
          } else {
			var i = 1;  
            nodes.forEach(function(n) {
              //TODO: q4 noch zu dd2 umändern -> entsprechende Zuordnung zur Datenbank im Handler am Ende
			  //Collect Data
			  if (n.index > 0) {
                if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p1_dd2[i] = n.dd2 = "gar nicht";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y) {
                  p1_dd2[i] = n.dd2 = "einmal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y) {
                  p1_dd2[i] = n.dd2 = "zweimal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y) {
                  p1_dd2[i] = n.dd2 = "einmal im Monat";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p1_dd2[i] = n.dd2 = "einmal in der Woche";
                } else if (n.y > boxbar_offset_y) {
                 p1_dd2[i] = n.dd2 = "mehrmals pro Woche";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
          
            document.getElementById("labelBar1").style.display = "none";
            document.getElementById("sixBar").style.display = "none";  	
            document.getElementById("slide6").style.display = "none";
			document.getElementById("slide7").style.display = "block";			
     
            currSlide++; 
          }

//Token		
//----------------------präferrierte Version-----------------------------------------------
	}else if (currSlide == 9){
		document.getElementById("slide7").style.display = "none"; 
		// Fix nodes in preparation for individual questions
		d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
		d3.selectAll(".node").style("display", "block");
		
		//Skip to next Question if askedAbout == number of Friends
		if (askedAbout == numFriends){
			currSlide = 9.5;
			artKommunikation.style.display = "none";
			skipQuestions();

		}else{
			//Part 1 Question 1 Friend 1		
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question1_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question1_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202) //- 202
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien erhalten haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20); 
			  //.style("display", "none");		  
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			artKommunikation.style.display = "block";
		}
		
	 }else if (currSlide == 9.1){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p1_q1_n1_1 = nodes1[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p1_q1_n1_2 = nodes1[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p1_q1_n1_3 = nodes1[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p1_q1_n1_4 = nodes1[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p1_q1_n1_5 = nodes1[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p1_q1_n1_6 = nodes1[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p1_q1_n1_7 = nodes1[askedAbout].q1_7 = "anders";
              }
			  
			checked = false;
			document.getElementById("backdrop1").style.display = "none";
			document.getElementById("question1_text1").style.display = "none";
			document.getElementById("question1_window").style.display = "none";
			refreshRadio();
	
			if (askedAbout == numFriends) {
			currSlide = 9.5;
			artKommunikation.style.display = "none";
			skipQuestions();
				
			} else {		
			//Part 1 Question 1 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question2_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question2_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien erhalten haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}
	 }else if (currSlide ==	9.2){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p1_q1_n2_1 = nodes1[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p1_q1_n2_2= nodes1[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p1_q1_n2_3 = nodes1[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p1_q1_n2_4 = nodes1[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p1_q1_n2_5 = nodes1[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p1_q1_n2_6 = nodes1[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p1_q1_n2_7 = nodes1[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("backdrop2").style.display = "none";
			document.getElementById("question2_text1").style.display = "none";
			document.getElementById("question2_window").style.display = "none";
			refreshRadio();
		    
			if (askedAbout == numFriends) {
			currSlide = 9.5;
			//artKommunikation.style.display = "none";
			artKommunikation.style.display = "none";
			skipQuestions();
			  
            } else {
			// Part 1 Question 1 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question3_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question3_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien erhalten haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 9.3;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide ==	9.3){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p1_q1_n3_1 = nodes1[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p1_q1_n3_2= nodes1[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p1_q1_n3_3 = nodes1[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p1_q1_n3_4 = nodes1[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p1_q1_n3_5 = nodes1[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p1_q1_n3_6 = nodes1[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p1_q1_n3_7 = nodes1[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("backdrop3").style.display = "none";
			document.getElementById("question3_text1").style.display = "none";
			document.getElementById("question3_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
			currSlide = 9.5;
			//artKommunikation.style.display = "none";
			artKommunikation.style.display = "none";
			skipQuestions();
			  
            } else {	
			// Part 1 Question 1 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question4_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question4_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien erhalten haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if(currSlide == 9.4){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p1_q1_n4_1 = nodes1[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p1_q1_n4_2= nodes1[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p1_q1_n4_3 = nodes1[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p1_q1_n4_4 = nodes1[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p1_q1_n4_5 = nodes1[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p1_q1_n4_6 = nodes1[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p1_q1_n4_7 = nodes1[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("backdrop4").style.display = "none";
			document.getElementById("question4_text1").style.display = "none";
			document.getElementById("question4_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
			currSlide = 9.5;
			//artKommunikation.style.display = "none";
			artKommunikation.style.display = "none";
			skipQuestions();
	
            } else {
			// Part 1 Question 1 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question5_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question5_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien erhalten haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 9.5){
		var kommunikationForm = document.getElementById("artDerKommunikation"); 	
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
			
			//Collect Data	
              if (kommunikationForm[0].checked) {
               p1_q1_n5_1 = nodes1[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p1_q1_n5_2= nodes1[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p1_q1_n5_3 = nodes1[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p1_q1_n5_4 = nodes1[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p1_q1_n5_5 = nodes1[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p1_q1_n5_6 = nodes1[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p1_q1_n5_7 = nodes1[askedAbout].q1_7 = "anders";
              }
  
				document.getElementById("backdrop5").style.display = "none";
				document.getElementById("question5_text1").style.display = "none";
				document.getElementById("question5_window").style.display = "none";
				artKommunikation.style.display = "none";
				refreshRadio();
				}
				
			// Part 1 Question 2 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question1_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question1_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen von '" + nodes[askedAbout].name + "' erhalten haben.")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			themaErhalteneInfos.style.display = "block";
		}
		
	}else if (currSlide == 9.6){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p1_q2_n1 = nodes1[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("backdrop2_1").style.display = "none";
			document.getElementById("question1_text2").style.display = "none";
			document.getElementById("question1_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 10;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 1 Question 2 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question2_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2_2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question2_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen von '" + nodes[askedAbout].name + "' erhalten haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	} else if (currSlide == 9.7){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p1_q2_n2 = nodes1[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("backdrop2_2").style.display = "none";
			document.getElementById("question2_text2").style.display = "none";
			document.getElementById("question2_window2").style.display = "none";
			refreshRadio();
				
		if (askedAbout == numFriends){
			currSlide = 10;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
			} else {		
			// Part 1 Question 2 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question3_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2_3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question3_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen von '" + nodes[askedAbout].name + "' erhalten haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 9.8;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if (currSlide == 9.8){
	// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
				
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p1_q2_n3 = nodes1[askedAbout].q2 = erhInfoInput.value;
              }			
			checked = false;
			document.getElementById("backdrop2_3").style.display = "none";
			document.getElementById("question3_text2").style.display = "none";
			document.getElementById("question3_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 10;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 1 Question 2 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question4_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2_4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question4_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen von '" + nodes[askedAbout].name + "' erhalten haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
		
	}else if (currSlide == 9.9){
// If user has not selected an option, alert with popup

		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p1_q2_n4 = nodes1[askedAbout].q2 = erhInfoInput.value;
              }
			  
			checked = false;
			document.getElementById("backdrop2_4").style.display = "none";
			document.getElementById("question4_text2").style.display = "none";
			document.getElementById("question4_window2").style.display = "none";
			refreshRadio();

		if (askedAbout == numFriends){
			currSlide = 10;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
				
		} else {		
			// Part 1 Question 2 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question5_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop2_5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question5_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen von '" + nodes[askedAbout].name + "' erhalten haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
		
	}else if (currSlide == 10){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
				
        } else {
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p1_q2_n5 = nodes1[askedAbout].q2 = erhInfoInput.value;
              }
				
				document.getElementById("backdrop2_5").style.display = "none";
				document.getElementById("question5_text2").style.display = "none";
				document.getElementById("question5_window2").style.display = "none";
				themaErhalteneInfos.style.display = "none";
				refreshRadio();
			}
			
			// Part 1 Question 3 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question1_window3")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop3_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question1_text3")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien, die Sie an '" + nodes[askedAbout].name + "' weitergegeben haben, hauptsächlich zuordnen?")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			digiSchulentwicklung.style.display = "block";
		}
		
	}else if(currSlide ==10.1){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
		 	
        }else {
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes1[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes1[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes1[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes1[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes1[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes1[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}
 
			checked = false;
			document.getElementById("backdrop3_1").style.display = "none";
			document.getElementById("question1_text3").style.display = "none";
			document.getElementById("question1_window3").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 10.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 1 Question 3 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question2_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop3_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question2_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien, die Sie an '" + nodes[askedAbout].name + "' weitergegeben haben, hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if (currSlide == 10.2){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else {
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes1[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes1[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes1[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes1[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes1[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes1[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}			
			checked = false;
			document.getElementById("backdrop3_2").style.display = "none";
			document.getElementById("question2_text3").style.display = "none";
			document.getElementById("question2_window3").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 10.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
				
			}else{		
			// Part 1 Question 3 Friend 3		
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question3_window3")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop3_3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question3_text3")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien, die Sie an '" + nodes[askedAbout].name + "' weitergegeben haben, hauptsächlich zuordnen?")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			currSlide = 10.3;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if (currSlide==10.3){
	var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else {
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes1[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes1[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes1[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes1[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes1[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes1[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}			
			checked = false;
			document.getElementById("backdrop3_3").style.display = "none";
			document.getElementById("question3_text3").style.display = "none";
			document.getElementById("question3_window3").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 10.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
								
			}else{	
				// Part 1 Question 3 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question4_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop3_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question4_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien, die Sie an '" + nodes[askedAbout].name + "' weitergegeben haben, hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 10.4){
	var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes1[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes1[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes1[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes1[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes1[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes1[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}			
			checked = false;
			document.getElementById("backdrop3_4").style.display = "none";
			document.getElementById("question4_text3").style.display = "none";
			document.getElementById("question4_window3").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 10.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
				
			}else{			
				// Part 1 Question 3 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question5_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop3_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question5_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien, die Sie an '" + nodes[askedAbout].name + "' weitergegeben haben, hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 10.5){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes1[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes1[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes1[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes1[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes1[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes1[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}				
				document.getElementById("backdrop3_5").style.display = "none";
				document.getElementById("question5_text3").style.display = "none";
				document.getElementById("question5_window3").style.display = "none";
				digiSchulentwicklung.style.display = "none";
				refreshRadio();
			}	
			// Part 1 Question 4 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question1_window4")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop4_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 500);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question1_text4")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			funktionenPersonen.style.display = "block";
		}
		
	}else if(currSlide == 10.6){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p1_q4_n1_1 = nodes1[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p1_q4_n1_2= nodes1[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p1_q4_n1_3 = nodes1[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p1_q4_n1_4 = nodes1[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p1_q4_n1_5 = nodes1[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p1_q4_n1_6 = nodes1[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p1_q4_n1_7 = nodes1[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p1_q4_n1_8= nodes1[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p1_q4_n1_9 = nodes1[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p1_q4_n1_10 = nodes1[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p1_q4_n1_11 = nodes1[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p1_q4_n1_12 = nodes1[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p1_q4_n1_13 = nodes1[askedAbout].q4_13 = inputFunktionen.value;
              }
			  
			checked = false;
			document.getElementById("backdrop4_1").style.display = "none";
			document.getElementById("question1_text4").style.display = "none";
			document.getElementById("question1_window4").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11;
				funktionenPersonen.style.display = "none";
				skipQuestions();
							
			}else{
				// Part 1 Question 4 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question2_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop4_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question2_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 10.7){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p1_q4_n2_1 = nodes1[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p1_q4_n2_2= nodes1[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p1_q4_n2_3 = nodes1[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p1_q4_n2_4 = nodes1[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p1_q4_n2_5 = nodes1[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p1_q4_n2_6 = nodes1[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p1_q4_n2_7 = nodes1[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p1_q4_n2_8= nodes1[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p1_q4_n2_9 = nodes1[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p1_q4_n2_10 = nodes1[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p1_q4_n2_11 = nodes1[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p1_q4_n2_12 = nodes1[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p1_q4_n2_13 = nodes1[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("backdrop4_2").style.display = "none";
			document.getElementById("question2_text4").style.display = "none";
			document.getElementById("question2_window4").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11;
				funktionenPersonen.style.display = "none";
				skipQuestions();				
			}else{				
				// Part 1 Question 4 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question3_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop4_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question3_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 10.8;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 10.8){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p1_q4_n3_1 = nodes1[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p1_q4_n3_2= nodes1[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p1_q4_n3_3 = nodes1[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p1_q4_n3_4 = nodes1[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p1_q4_n3_5 = nodes1[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p1_q4_n3_6 = nodes1[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p1_q4_n3_7 = nodes1[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p1_q4_n3_8= nodes1[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p1_q4_n3_9 = nodes1[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p1_q4_n3_10 = nodes1[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p1_q4_n3_11 = nodes1[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p1_q4_n3_12 = nodes1[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p1_q4_n3_13 = nodes1[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("backdrop4_3").style.display = "none";
			document.getElementById("question3_text4").style.display = "none";
			document.getElementById("question3_window4").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11;
				funktionenPersonen.style.display = "none";
				skipQuestions();
							
			}else{				
				// Part 1 Question 4 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question4_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop4_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question4_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 10.9){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p1_q4_n4_1 = nodes1[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p1_q4_n4_2= nodes1[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p1_q4_n4_3 = nodes1[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p1_q4_n4_4 = nodes1[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p1_q4_n4_5 = nodes1[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p1_q4_n4_6 = nodes1[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p1_q4_n4_7 = nodes1[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p1_q4_n4_8= nodes1[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p1_q4_n4_9 = nodes1[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p1_q4_n4_10 = nodes1[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p1_q4_n4_11 = nodes1[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p1_q4_n4_12 = nodes1[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p1_q4_n4_13 = nodes1[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("backdrop4_4").style.display = "none";
			document.getElementById("question4_text4").style.display = "none";
			document.getElementById("question4_window4").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{					
				// Part 1 Question 4 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question5_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop4_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question5_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 11){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") &&!checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p1_q4_n5_1 = nodes1[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p1_q4_n5_2= nodes1[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p1_q4_n5_3 = nodes1[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p1_q4_n5_4 = nodes1[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p1_q4_n5_5 = nodes1[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p1_q4_n5_6 = nodes1[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p1_q4_n5_7 = nodes1[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p1_q4_n5_8= nodes1[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p1_q4_n5_9 = nodes1[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p1_q4_n5_10 = nodes1[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p1_q4_n5_11 = nodes1[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p1_q4_n5_12 = nodes1[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p1_q4_n5_13 = nodes1[askedAbout].q4_13 = inputFunktionen.value;
              }
				
				document.getElementById("backdrop4_5").style.display = "none";
				document.getElementById("question5_text4").style.display = "none";
				document.getElementById("question5_window4").style.display = "none";
				funktionenPersonen.style.display = "none";
				refreshRadio();
			}
			
			// Part 1 Question 5 Friend 1		
			askedAbout = 0;
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "question1_window5")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "backdrop5_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 250);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "question1_text5")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			wahrgenLernmöglichkeiten.style.display = "block";
		}
		
	}else if(currSlide == 11.1){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		
		// If user has not selected an option, alert with popup		
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked /*&& !skipped*/) {
            promptNonresponse();
            checked = true;
			
        } else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes1[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes1[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes1[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes1[askedAbout].q7 = "nein";
            }
			
			//console.log(inputValue);
			checked = false;
			document.getElementById("backdrop5_1").style.display = "none";
			document.getElementById("question1_text5").style.display = "none";
			document.getElementById("question1_window5").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{				
				// Part 1 Question 5 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question2_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop5_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question2_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 11.2){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked /*&& !skipped*/) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes1[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes1[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes1[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes1[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("backdrop5_2").style.display = "none";
			document.getElementById("question2_text5").style.display = "none";
			document.getElementById("question2_window5").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{				
				// Part 1 Question 5 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question3_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop5_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question3_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 11.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 11.3){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked /*&& !skipped*/) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes1[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes1[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes1[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes1[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("backdrop5_3").style.display = "none";
			document.getElementById("question3_text5").style.display = "none";
			document.getElementById("question3_window5").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
								
			}else{				
				// Part 1 Question 5 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question4_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop5_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question4_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 11.4){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked /*&& !skipped*/) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes1[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes1[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes1[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes1[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("backdrop5_4").style.display = "none";
			document.getElementById("question4_text5").style.display = "none";
			document.getElementById("question4_window5").style.display = "none";
			refreshRadio();
				
			if (askedAbout == numFriends){
				currSlide = 11.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
							
			}else{			
				// Part 1 Question 5 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "question5_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "backdrop5_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "question5_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}	
		
	}else if(currSlide == 11.5){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){			
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes1[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes1[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes1[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes1[askedAbout].q7 = "nein";
            }
			
				skipped = false;
				checked = false;
				document.getElementById("backdrop5_5").style.display = "none";
				document.getElementById("question5_text5").style.display = "none";
				document.getElementById("question5_window5").style.display = "none";
				wahrgenLernmöglichkeiten.style.display = "none";
				refreshRadio();
				
				currSlide+= 1.5;
				console.log("aktuelle Slide: " + currSlide);
				showNext();
				
			}else{
				skipped = false;
				checked = false;
				currSlide+= 1.5;
				refreshRadio();
				console.log("aktuelle Slide: " + currSlide);
				showNext();
			}
	}		
		
//-----------------------------------------------------------		  
//Slide für Beziehunen zwischen den Personen - Links
//----------------------------------------------------------	

	}else if(currSlide == 13){ 
			//Nodes fixieren 
			d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
			restart();
			
			 // Q5: Which of these people know each other?
            
			document.getElementById("slide7").style.display = "none";
            document.getElementById("slide12").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;
            
            if (numFriends < 2) {
              showNext();
            }
	
	}else if (currSlide == 14){
			
			//vorherige Slide ausblenden
			document.getElementById("slide12").style.display = "none";
			
			//hides nodes and links,
			d3.selectAll(".link").attr("display", "none"); 
			d3.selectAll(".node").style("display", "none");
			
			//Fixierung lösen notwendig?
			d3.selectAll(".node").classed("fixed", function(d) {  
                if (d.index > 0 ) {
                  d.fixed = false
                }
              });
			  
			//Collect Data
			for (var i= 1; i <= numFriends; i++){
				p1_link[i] = nodes[i].friendsWith;
			}
			
			document.getElementById("slide14").style.display = "block";

			currSlide++;
	
//---------------------------2. Durchgang-------------------------------------------------------

	} else if (currSlide == 15){
		checked = false;
		skipped = false;
		askedAbout = 0;
		numFriends = 0;
		resetGraph();

			document.getElementById("slide14").style.display = "none";
			
			d3.selectAll(".node").attr("display", "block");
			d3.selectAll(".node").on('mousedown.drag', function(d) {
            return d.index > 0 ? true : null;
			});

			document.getElementById("slide15").style.display = "block";
			document.getElementById("name_input").style.display = "block";
			document.getElementById("name_input").style.left = string_l + "px";

			currSlide++;
			
    } else if (currSlide == 16) {
          if (numFriends < 5 && checked == false) {
            checked = true;
            console.log("fewer than 5 friends")
            friendPromptNonresponse();
			
        }else if (numFriends == 0 && checked == true){
			//Skipps to the next question about Persons
			currSlide = 33.5;
			document.getElementById("slide15").style.display = "none";
			document.getElementById("name_input").style.display = "none";
			//restart();
			showNext();		
		}else {
			//Collect Data
			for(var i = 1; i<= numFriends; i++){
				p2_name[i] = nodes[i].name;
				console.log("name" +i+ ": " + p2_name[i]);
			}
			for(var i = 1; i<= numFriends; i++){
				nodes2[i] = nodes[i];
			}
			
            checked = false;
            document.getElementById("slide15").style.display = "none";
            document.getElementById("slide4").style.display = "block";
            
			//das hier notwendig?
			var text = $("#slide4 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide4 .numfri").text(text);
            
            document.getElementById("name_input").style.display = "none";
            currSlide++;
          }
    } else if (currSlide == 17){
		document.getElementById("slide4").style.display = "none";
		//prepare nodes for dragging into boxes
		/*	
          d3.selectAll(".node").style("display", "block");
          clearColors();
          node[0].y -= 100;
          restart();
		  */
		  
          // Q2: Welcher Gruppe gehören die von Ihnen genannten Personen an?
		  document.getElementById("slide5").style.display = "block";
		  document.getElementById("tenBar").style.display = "block";
		  document.getElementById("labelBar2").style.display = "block";
		  
          var text = $("#slide5 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide5 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide5 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000); 

          currSlide++;
	} else if(currSlide == 18){
		var nodeAbove = false;
          var nodeBelow = false;

          //Make sure the nodes are correctly placed in one of the boxes - shows NonResponse message if Nodes are not correctly placed
          //TODO: für tenBar entsprechend anpassen, da 1. und 8. Box neu angeordnet
		  nodes.forEach(function(n) {
            if (n.index > 0) {
              if (((n.x > boxbar_offset_x + bar6_target_width) && (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)) && (n.y < boxbar_offset_y)) || (n.y < (boxbar_offset_y - 450))){
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });
		  

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;
            nodes.forEach(function(n) {
              //TODO: abweichende Positionierung der 1. und 8. Box, boxbar_offset_y etc. entsprechend anpassen!
			  //Collect Data
			  if (n.index > 0) {
				if (n.x < boxbar_offset_x + bar6_target_width && n.y< (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*2) && (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*3)) {
                  p2_dd1[i] = n.dd1 = "Mitglied meiner Schule"; 
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height) && n.y > (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height*2)) {
                  p2_dd1[i] = n.dd1 = "Mitglied anderer Netzwerkschule";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p2_dd1[i] = n.dd1 = "SchülerIn";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p2_dd1[i] = n.dd1 = "externe Medienberatung";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y){
                  p2_dd1[i] = n.dd1 = "Schulträger";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y){
                  p2_dd1[i] = n.dd1 = "externe Fortbildungsakteure";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y){
                  p2_dd1[i] = n.dd1 = "Learning Lab";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p2_dd1[i] = n.dd1 = "Elternvertreter";
				} else if (n.y > boxbar_offset_y){
                  p2_dd1[i] = n.dd1 = "Bezirksregierung";
                } else if (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + bar6_target_width) && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p2_dd1[i] = n.dd1 = "Sonstiges";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
          
            document.getElementById("labelBar2").style.display = "none";
            document.getElementById("tenBar").style.display = "none";  	
            document.getElementById("slide5").style.display = "none";  
			currSlide++;
			showNext();
          }
	} 
	
	else if (currSlide == 19){
         // Q4: Welcher Gruppe gehören die von Ihnen genannten Personen an?
			
          document.getElementById("slide6").style.display = "block";		
          document.getElementById("sixBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";
          
		  
          var text = $("#slide6 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide6 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide6 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000);

          currSlide++;
        
		} else if (currSlide == 20) {
          var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes
          nodes.forEach(function(n) {
            if (n.index > 0) {
              if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              } 
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;	
            nodes.forEach(function(n) {
              //TODO: q4 noch zu dd2 umändern -> entsprechende Zuordnung zur Datenbank im Handler am Ende
			  //Collect Data
			  if (n.index > 0) {
                if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p2_dd2[i] = n.dd2 = "gar nicht";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y) {
                  p2_dd2[i] = n.dd2 = "einmal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y) {
                  p2_dd2[i] = n.dd2 = "zweimal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y) {
				  p2_dd2[i] = n.dd2 = "einmal im Monat";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p2_dd2[i] = n.dd2 = "einmal in der Woche";
                } else if (n.y > boxbar_offset_y) {
                  p2_dd2[i] = n.dd2 = "mehrmals pro Woche";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
			
			currSlide++; 
			
			document.getElementById("slide6").style.display = "none";		
			document.getElementById("sixBar").style.display = "none";
			document.getElementById("labelBar1").style.display = "none";
			document.getElementById("slide7").style.display = "block";
		  }
		  
//Token1		  
	} else if (currSlide == 21){
		document.getElementById("slide7").style.display = "none"; 
		// Fix nodes in preparation for individual questions
		d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
		d3.selectAll(".node").style("display", "block");	
		
		if (askedAbout == numFriends) {
			currSlide = 21.5;
			artKommunikation.style.display = "none";
			skipQuestions();
				
		} else {
			// Part 2 Question 1 Friend 1		
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question1_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question1_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien weitergegeben haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			  //.style("display", "none");		  
			
			console.log("aktuelle Slide: " + currSlide);
			drawBox(currNode);
			artKommunikation.style.display = "block";
			//document.getElementById("artDerKommunikation").style.display = "block";		
		}
		
	 }else if (currSlide == 21.1){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p2_q1_n1_1 = nodes2[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p2_q1_n1_2= nodes2[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p2_q1_n1_3 = nodes2[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p2_q1_n1_4 = nodes2[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p2_q1_n1_5 = nodes2[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p2_q1_n1_6 = nodes2[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p2_q1_n1_7 = nodes2[askedAbout].q1_7 = "anders";
              }
			  
			checked = false;
			document.getElementById("2_backdrop1").style.display = "none";
			document.getElementById("2_question1_text1").style.display = "none";
			document.getElementById("2_question1_window").style.display = "none";
			refreshRadio();
	
			if (askedAbout == numFriends) {
				currSlide = 21.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {		
			// Part 2 Question 1 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question2_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question2_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien weitergegeben haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
				
			console.log("aktuelle Slide: " + currSlide);
			currSlide= 21.2;
			}
		}
	 }else if (currSlide ==	21.2){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p2_q1_n2_1 = nodes2[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p2_q1_n2_2= nodes2[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p2_q1_n2_3 = nodes2[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p2_q1_n2_4 = nodes2[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p2_q1_n2_5 = nodes2[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p2_q1_n2_6 = nodes2[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p2_q1_n2_7 = nodes2[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("2_backdrop2").style.display = "none";
			document.getElementById("2_question2_text1").style.display = "none";
			document.getElementById("2_question2_window").style.display = "none";
			refreshRadio();
		    
			if (askedAbout == numFriends) {
				currSlide = 21.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 2 Question 1 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question3_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question3_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien weitergegeben haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 21.3;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	 }else if (currSlide ==	21.3){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p2_q1_n3_1 = nodes2[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p2_q1_n3_2= nodes2[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p2_q1_n3_3 = nodes2[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p2_q1_n3_4 = nodes2[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p2_q1_n3_5 = nodes2[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p2_q1_n3_6 = nodes2[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p2_q1_n3_7 = nodes2[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("2_backdrop3").style.display = "none";
			document.getElementById("2_question3_text1").style.display = "none";
			document.getElementById("2_question3_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 21.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 2 Question 1 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question4_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question4_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien weitergegeben haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			
			currSlide = 21.4;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	 }else if(currSlide == 21.4){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p2_q1_n4_1 = nodes2[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p2_q1_n4_2= nodes2[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p2_q1_n4_3 = nodes2[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p2_q1_n4_4 = nodes2[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p2_q1_n4_5 = nodes2[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p2_q1_n4_6 = nodes2[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p2_q1_n4_7 = nodes2[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("2_backdrop4").style.display = "none";
			document.getElementById("2_question4_text1").style.display = "none";
			document.getElementById("2_question4_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 21.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 2 Question 1 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question5_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question5_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wenn Sie Informationen über Praxisbeispiele, Maßnahmen oder Strategien weitergegeben haben, wie haben sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			
			console.log("aktuelle Slide: " + currSlide);
			}
		  }	
	}else if(currSlide == 21.5){		
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p2_q1_n5_1 = nodes2[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p2_q1_n5_2= nodes2[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p2_q1_n5_3 = nodes2[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p2_q1_n5_4 = nodes2[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p2_q1_n5_5 = nodes2[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p2_q1_n5_6 = nodes2[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p2_q1_n5_7 = nodes2[askedAbout].q1_7 = "anders";
              }
			
				document.getElementById("2_backdrop5").style.display = "none";
				document.getElementById("2_question5_text1").style.display = "none";
				document.getElementById("2_question5_window").style.display = "none";
				artKommunikation.style.display = "none";
				refreshRadio();
			}
			
		// Part 2 Question 2 Friend 1		
		askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
		askedAbout++;
		d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
		currNode = nodes[askedAbout];
		
		d3.select("svg").append("rect")
          .attr("class", "q_window")
          .attr("id", "2_question1_window2")
          .attr("rx", 2)
          .attr("ry", 2)
          .attr("width", q_window_width)
          .attr("height", q_window_height)
          .attr("x", currNode.x - q_window_width / 2)
          .attr("y", currNode.y - q_window_height / 2);

		d3.select("svg").append("rect")
		  .attr("class", "backdrop")
          .attr("id", "2_backdrop2_1")
          .attr("x", currNode.x - q_window_width / 2 - 110)
          .attr("y", currNode.y - 240)
          .attr("width", backdrop_width)
          .attr("height", 200);		  
	
        d3.select("svg").append("text")
          .attr("class", "slideText")
          .attr("id", "2_question1_text2")
          .attr("x", currNode.x - q_window_width / 2 - 100)
          .attr("dy", currNode.y - 202)
          .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen an '" + nodes[askedAbout].name + "' weitergegeben haben.")
          .call(wrap2, backdrop_width - 20);

		drawBox(currNode);
		console.log("aktuelle Slide: " + currSlide);
		themaErhalteneInfos.style.display = "block";
		}
		
	}else if (currSlide == 21.6){
		// If user has not selected an option, alert with popup

		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p2_q2_n1 = nodes2[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop2_1").style.display = "none";
			document.getElementById("2_question1_text2").style.display = "none";
			document.getElementById("2_question1_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 22;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 2 Question 2 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question2_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop2_2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question2_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen an '" + nodes[askedAbout].name + "' weitergegeben haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 21.7;
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	} else if (currSlide == 21.7){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p2_q2_n2 = nodes2[askedAbout].q2 = erhInfoInput.value;
              }
			  
			checked = false;
			document.getElementById("2_backdrop2_2").style.display = "none";
			document.getElementById("2_question2_text2").style.display = "none";
			document.getElementById("2_question2_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 22;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 2 Question 2 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question3_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop2_3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question3_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen an '" + nodes[askedAbout].name + "' weitergegeben haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 21.8;
			console.log("aktuelle Slide: " + currSlide);
				
			
			}
		}
	}else if (currSlide == 21.8){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
				
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p2_q2_n3 = nodes2[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop2_3").style.display = "none";
			document.getElementById("2_question3_text2").style.display = "none";
			document.getElementById("2_question3_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 22;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 2 Question 2 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question4_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop2_4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question4_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen an '" + nodes[askedAbout].name + "' weitergegeben haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 21.9;
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 21.9){
		// If user has not selected an option, alert with popup			
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
				
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p2_q2_n4 = nodes2[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop2_4").style.display = "none";
			document.getElementById("2_question4_text2").style.display = "none";
			document.getElementById("2_question4_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 22;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 2 Question 2 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question5_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop2_5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question5_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie Informationen an '" + nodes[askedAbout].name + "' weitergegeben haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 22){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
				
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
				//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p2_q2_n5 = nodes2[askedAbout].q2 = erhInfoInput.value;
              }
				
				document.getElementById("2_backdrop2_5").style.display = "none";
				document.getElementById("2_question5_text2").style.display = "none";
				document.getElementById("2_question5_window2").style.display = "none";
				themaErhalteneInfos.style.display = "none";
				refreshRadio();
			}
				
			// Part 2 Question 3 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question1_window3")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop3_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question1_text3")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien hauptsächlich zuordnen?")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			digiSchulentwicklung.style.display = "block";
		}
		
	}else if(currSlide ==22.1){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes2[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes2[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes2[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes2[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes2[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes2[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("2_backdrop3_1").style.display = "none";
			document.getElementById("2_question1_text3").style.display = "none";
			document.getElementById("2_question1_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 22.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 2 Question 3 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question2_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop3_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question2_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.2;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 22.2){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes2[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes2[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes2[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes2[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes2[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes2[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("2_backdrop3_2").style.display = "none";
			document.getElementById("2_question2_text3").style.display = "none";
			document.getElementById("2_question2_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 22.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 2 Question 3 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question3_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop3_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question3_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if (currSlide==22.3){
	var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes2[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes2[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes2[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes2[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes2[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes2[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("2_backdrop3_3").style.display = "none";
			document.getElementById("2_question3_text3").style.display = "none";
			document.getElementById("2_question3_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 22.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 2 Question 3 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question4_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop3_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question4_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.4;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 22.4){
	var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!  
			if (schulentwicklungForm[0].checked) {
				nodes2[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes2[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes2[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes2[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes2[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes2[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("2_backdrop3_4").style.display = "none";
			document.getElementById("2_question4_text3").style.display = "none";
			document.getElementById("2_question4_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 22.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 2 Question 3 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question5_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop3_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question5_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Informationen über Praxisbeispiele, Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.5;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 22.5){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;			
				//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes2[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes2[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes2[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes2[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes2[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes2[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
				document.getElementById("2_backdrop3_5").style.display = "none";
				document.getElementById("2_question5_text3").style.display = "none";
				document.getElementById("2_question5_window3").style.display = "none";
				digiSchulentwicklung.style.display = "none";
				refreshRadio();
			}
			
			// Part 2 Question 4 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question1_window4")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop4_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 500);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question1_text4")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			currSlide = 22.6;
			console.log("aktuelle Slide: " + currSlide);
			funktionenPersonen.style.display = "block";
		}
		
	}else if(currSlide == 22.6){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p2_q4_n1_1 = nodes2[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p2_q4_n1_2= nodes2[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p2_q4_n1_3 = nodes2[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p2_q4_n1_4 = nodes2[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p2_q4_n1_5 = nodes2[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p2_q4_n1_6 = nodes2[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p2_q4_n1_7 = nodes2[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p2_q4_n1_8= nodes2[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p2_q4_n1_9 = nodes2[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p2_q4_n1_10 = nodes2[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p2_q4_n1_11 = nodes2[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p2_q4_n1_12 = nodes2[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p2_q4_n1_13 = nodes2[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop4_1").style.display = "none";
			document.getElementById("2_question1_text4").style.display = "none";
			document.getElementById("2_question1_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{
				// Part 2 Question 4 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question2_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop4_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question2_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.7;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 22.7){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p2_q4_n2_1 = nodes2[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p2_q4_n2_2= nodes2[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p2_q4_n2_3 = nodes2[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p2_q4_n2_4 = nodes2[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p2_q4_n2_5 = nodes2[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p2_q4_n2_6 = nodes2[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p2_q4_n2_7 = nodes2[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p2_q4_n2_8= nodes2[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p2_q4_n2_9 = nodes2[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p2_q4_n2_10 = nodes2[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p2_q4_n2_11 = nodes2[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p2_q4_n2_12 = nodes2[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p2_q4_n2_13 = nodes2[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop4_2").style.display = "none";
			document.getElementById("2_question2_text4").style.display = "none";
			document.getElementById("2_question2_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{		
				// Part 2 Question 4 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question3_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop4_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question3_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.8;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 22.8){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p2_q4_n3_1 = nodes2[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p2_q4_n3_2= nodes2[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p2_q4_n3_3 = nodes2[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p2_q4_n3_4 = nodes2[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p2_q4_n3_5 = nodes2[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p2_q4_n3_6 = nodes2[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p2_q4_n3_7 = nodes2[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p2_q4_n3_8= nodes2[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p2_q4_n3_9 = nodes2[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p2_q4_n3_10 = nodes2[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p2_q4_n3_11 = nodes2[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p2_q4_n3_12 = nodes2[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p2_q4_n3_13 = nodes2[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop4_3").style.display = "none";
			document.getElementById("2_question3_text4").style.display = "none";
			document.getElementById("2_question3_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{		
				// Part 2 Question 4 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question4_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop4_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question4_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 22.9;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 22.9){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p2_q4_n4_1 = nodes2[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p2_q4_n4_2= nodes2[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p2_q4_n4_3 = nodes2[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p2_q4_n4_4 = nodes2[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p2_q4_n4_5 = nodes2[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p2_q4_n4_6 = nodes2[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p2_q4_n4_7 = nodes2[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p2_q4_n4_8= nodes2[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p2_q4_n4_9 = nodes2[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p2_q4_n4_10 = nodes2[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p2_q4_n4_11 = nodes2[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p2_q4_n4_12 = nodes2[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p2_q4_n4_13 = nodes2[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("2_backdrop4_4").style.display = "none";
			document.getElementById("2_question4_text4").style.display = "none";
			document.getElementById("2_question4_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{
				// Part 2 Question 4 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question5_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop4_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question5_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 23){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;			
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p2_q4_n5_1 = nodes2[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p2_q4_n5_2= nodes2[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p2_q4_n5_3 = nodes2[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p2_q4_n5_4 = nodes2[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p2_q4_n5_5 = nodes2[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p2_q4_n5_6 = nodes2[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p2_q4_n5_7 = nodes2[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p2_q4_n5_8= nodes2[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p2_q4_n5_9 = nodes2[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p2_q4_n5_10 = nodes2[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p2_q4_n5_11 = nodes2[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p2_q4_n5_12 = nodes2[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p2_q4_n5_13 = nodes2[askedAbout].q4_13 = inputFunktionen.value;
              }
				
				document.getElementById("2_backdrop4_5").style.display = "none";
				document.getElementById("2_question5_text4").style.display = "none";
				document.getElementById("2_question5_window4").style.display = "none";
				funktionenPersonen.style.display = "none";
				refreshRadio();
			}
			// Part 2 Question 5 Friend 1		
			askedAbout = 0;
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "2_question1_window5")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "2_backdrop5_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 250);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "2_question1_text5")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			wahrgenLernmöglichkeiten.style.display = "block";
		}
		
	}else if(currSlide == 23.1){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        } else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes2[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes2[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes2[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes2[askedAbout].q7 = "nein";
            }
			
			//console.log(inputValue);
			checked = false;
			document.getElementById("2_backdrop5_1").style.display = "none";
			document.getElementById("2_question1_text5").style.display = "none";
			document.getElementById("2_question1_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{		
				// Part 2 Question 5 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question2_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop5_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question2_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 23.2;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 23.2){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes2[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes2[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes2[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes2[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("2_backdrop5_2").style.display = "none";
			document.getElementById("2_question2_text5").style.display = "none";
			document.getElementById("2_question2_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 2 Question 5 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question3_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop5_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question3_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 23.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 23.3){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");	
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes2[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes2[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes2[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes2[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("2_backdrop5_3").style.display = "none";
			document.getElementById("2_question3_text5").style.display = "none";
			document.getElementById("2_question3_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{		
				// Part 2 Question 5 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question4_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop5_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question4_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 23.4;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 23.4){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes2[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes2[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes2[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes2[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("2_backdrop5_4").style.display = "none";
			document.getElementById("2_question4_text5").style.display = "none";
			document.getElementById("2_question4_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 23.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 2 Question 5 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "2_question5_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "2_backdrop5_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "2_question5_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 23.5;
				console.log("aktuelle Slide: " + currSlide);
			}
		}	
		
	}else if(currSlide == 23.5){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes2[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes2[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes2[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes2[askedAbout].q7 = "nein";
            }
			
				skipped = false;
				checked = false;
				document.getElementById("2_backdrop5_5").style.display = "none";
				document.getElementById("2_question5_text5").style.display = "none";
				document.getElementById("2_question5_window5").style.display = "none";
				wahrgenLernmöglichkeiten.style.display = "none";
				refreshRadio();
				currSlide = 31;
				showNext();
				
			}else {
				skipped = false;
				checked = false;				
				refreshRadio();
				currSlide = 31;
				showNext();
			}
		}
		
//----------------------------------------------------------------------------------------------------
//-------Links between Persons that talk to each other about digitalisation---------------------------
//----------------------------------------------------------------------------------------------------	
	
	} else if (currSlide == 31){
		
		//Nodes fixieren 
			d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
			restart();
			
			 // Q5: Which of these people know each other?
            
			document.getElementById("slide7").style.display = "none";
            document.getElementById("slide12").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;
            
            if (numFriends < 2) {
              showNext();
            }
	
	}else if (currSlide == 32){
		
		var gradFormalisierung = document.getElementById("gradFormalisierung");
		var formalisierungForm = document.getElementById("gradDerFormalisierung");
		
			//vorherige Slide ausblenden
			document.getElementById("slide12").style.display = "none";
			
			//hides nodes and links,
			d3.selectAll(".link").attr("display", "none"); 
			d3.selectAll(".node").style("display", "none");
			
			//Fixierung lösen (notwendig?)
			d3.selectAll(".node").classed("fixed", function(d) {  
                if (d.index > 0 ) {
                  d.fixed = false
                }
              });
			
			//Collect Data
			for (var i= 1; i <= numFriends; i++){
				p2_link[i] = nodes[i].friendsWith;
			}  
			  
		numFriends = 0;
		restart(); 
			
        gradFormalisierung.style.left = string_l + "px";		
        gradFormalisierung.style.top = string_t;				
        gradFormalisierung.style.top = string_t;				
        gradFormalisierung.style.display = "block";
		console.log("aktuelle Slide: " + currSlide);
		  
		currSlide = 32.5;
		
	} else if (currSlide == 32.5){
		var formalisierungForm = document.getElementById("gradDerFormalisierung");
		if ($('input[name=gradDerFormalisierung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
		}else{
			//Collect Data
              if (formalisierungForm[0].checked) {
               p2_q6_1 = nodes[0].q6_1 = "geschieht auf regelmäßiger Basis";
              } if (formalisierungForm[1].checked) {
               p2_q6_2= nodes[0].q6_2 = "geschieht im Rahmen der ohnehin vorgegebenen Treffen";
              } if (formalisierungForm[2].checked) {
               p2_q6_3 = nodes[0].q6_3 = "geschieht eher informell und beiläufig";
              } if (formalisierungForm[3].checked) {
               p2_q6_4 = nodes[0].q6_4 = "es gibt dafür vorgesehene Arbeitszeiten/Treffen";
              }	if (formalisierungForm[4].checked) {
               p2_q6_5 = nodes[0].q6_5 = "geschieht über eine Lernplattform (z.B. moodle), auf die alle Lehrkräfte zugreifen können";
              }	if (formalisierungForm[5].checked) {
               p2_q6_6 = nodes[0].q6_6 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (formalisierungForm[6].checked) {
               p2_q6_7 = nodes[0].q6_7 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }if (formalisierungForm[7].checked) {
               p2_q6_8= nodes[0].q6_8 = "wird von der Schulleitung gefördert";
              } if (formalisierungForm[8].checked) {
               p2_q6_9 = nodes[0].q6_9 = "wird von der Schulleitung angeordnet";
              } 
			  
			document.getElementById("gradFormalisierung").style.display = "none";
			checked = false;
			currSlide = 33;
			showNext();
		}
	} else if (currSlide == 33){
		
		var rahmenbedingungenInfoweitergabe1 = document.getElementById("rahmenbedingungenInfoweitergabe1");
		var rahmenbedingungenForm1 = document.getElementById("rahmenDerInfoweitergabe1");
		
          rahmenbedingungenInfoweitergabe1.style.left = string_l + "px";		
          rahmenbedingungenInfoweitergabe1.style.top = string_t;				
          rahmenbedingungenInfoweitergabe1.style.top = string_t;				
          rahmenbedingungenInfoweitergabe1.style.display = "block";
		  console.log("aktuelle Slide: " + currSlide);
		  
		currSlide = 33.5;		
	} else if (currSlide == 33.5){
		var rahmenbedingungenForm1 = document.getElementById("rahmenDerInfoweitergabe1");
		if ($('input[name=rahmenDerInfoweitergabe1]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
		}else{
			//Collect Data
              if (rahmenbedingungenForm1[0].checked) {
               p2_q7_1 = nodes[0].q7_1 = "geschieht auf regelmäßiger Basis";
              } if (rahmenbedingungenForm1[1].checked) {
               p2_q7_2= nodes[0].q7_2 = "geschieht im Rahmen der ohnehin vorgegebenen Treffen";
              } if (rahmenbedingungenForm1[2].checked) {
               p2_q7_3 = nodes[0].q7_3 = "geschieht eher informell und beiläufig";
              } if (rahmenbedingungenForm1[3].checked) {
               p2_q7_4 = nodes[0].q7_4 = "es gibt dafür vorgesehene Arbeitszeiten/Treffen";
              }	if (rahmenbedingungenForm1[4].checked) {
               p2_q7_5 = nodes[0].q7_5 = "geschieht über eine Lernplattform (z.B. moodle), auf die alle Lehrkräfte zugreifen können";
              }	if (rahmenbedingungenForm1[5].checked) {
               p2_q7_6 = nodes[0].q7_6 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (rahmenbedingungenForm1[6].checked) {
               p2_q7_7 = nodes[0].q7_7 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }if (rahmenbedingungenForm1[7].checked) {
               p2_q7_8= nodes[0].q7_8 = "wird von der Schulleitung gefördert";
              } if (rahmenbedingungenForm1[8].checked) {
               p2_q7_9 = nodes[0].q7_9 = "wird von der Schulleitung angeordnet";
              } 
			  
			document.getElementById("rahmenbedingungenInfoweitergabe1").style.display = "none";
			document.getElementById("slide33").style.display = "block";
			checked = false;
			currSlide = 34;
		}
	}
//---------------------------3. Durchgang-------------------------------------------------------
	
	else if (currSlide == 34){
		// 3. Namenseingabe
			document.getElementById("slide33").style.display = "none";
				
			checked = false;
			skipped = false;
			numFriends = 0;
			askedAbout = 0;
			resetGraph();
				
			d3.selectAll(".node").attr("display", "block");
			d3.selectAll(".node").on('mousedown.drag', function(d) {
			return d.index > 0 ? true : null;
			});
					
			document.getElementById("slide34").style.display = "block";
			document.getElementById("name_input").style.display = "block";
			document.getElementById("name_input").style.left = string_l + "px";
			
		currSlide++;
			
    } else if (currSlide == 35) {
          if (numFriends < 5 && checked == false) {
            checked = true;
            console.log("fewer than 5 friends")
            friendPromptNonresponse();
			
          } else if (numFriends == 0 && checked == true){
			//Skipps to the next question about Persons
			currSlide = 52.5;
			document.getElementById("slide34").style.display = "none";
			document.getElementById("name_input").style.display = "none";
			restart();
			showNext();
			
		} else {
			//Collect Data
			for(var i = 1; i<= numFriends; i++){
				p3_name[i] = nodes[i].name;
				console.log("name" +i+ ": " + p3_name[i]);
			}	
			
			for(var i = 1; i<= numFriends; i++){
				nodes3[i] = nodes[i];
			}			
			
            checked = false;
            document.getElementById("slide34").style.display = "none";
            document.getElementById("slide4").style.display = "block";
            
			//das hier notwendig?
			var text = $("#slide4 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide4 .numfri").text(text);
            
            document.getElementById("name_input").style.display = "none";
            currSlide++;
          }
	  } else if (currSlide == 36){
		document.getElementById("slide4").style.display = "none";
	  	  document.getElementById("slide5").style.display = "block";
		  document.getElementById("tenBar").style.display = "block";
		  document.getElementById("labelBar2").style.display = "block";
		  
          var text = $("#slide5 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide5 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide5 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000); 

          currSlide++;
		  
	} else if(currSlide == 37){
		var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes - shows NonResponse message if Nodes are not correctly placed
          //TODO: für tenBar entsprechend anpassen, da 1. und 8. Box neu angeordnet
		  nodes.forEach(function(n) {
            if (n.index > 0) {
              if (((n.x > boxbar_offset_x + bar6_target_width) && (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)) && (n.y < boxbar_offset_y)) || (n.y < (boxbar_offset_y - 450))){
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });
		  
          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;
            nodes.forEach(function(n) {
              //TODO: abweichende Positionierung der 1. und 8. Box, boxbar_offset_y etc. entsprechend anpassen!
			  //Collect Data
			  if (n.index > 0) {
				if (n.x < boxbar_offset_x + bar6_target_width && n.y< (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*2) && (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*3)) {
                  p3_dd1[i] = n.dd1 = "Mitglied meiner Schule"; 
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height) && n.y > (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height*2)) {
                  p3_dd1[i] = n.dd1 = "Mitglied anderer Netzwerkschule";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p3_dd1[i] = n.dd1 = "SchülerIn";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p3_dd1[i] = n.dd1 = "externe Medienberatung";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y){
                  p3_dd1[i] = n.dd1 = "Schulträger";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y){
                  p3_dd1[i] = n.dd1 = "externe Fortbildungsakteure";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y){
                  p3_dd1[i] = n.dd1 = "Learning Lab";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p3_dd1[i] = n.dd1 = "Elternvertreter";
				} else if (n.y > boxbar_offset_y){
                  p3_dd1[i] = n.dd1 = "Bezirksregierung";
                } else if (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + bar6_target_width) && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p3_dd1[i] = n.dd1 = "Sonstiges";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
          
            document.getElementById("labelBar2").style.display = "none";
            document.getElementById("tenBar").style.display = "none";  	
            document.getElementById("slide5").style.display = "none";  
			currSlide++;
			showNext();
          }
		  
	  } else if (currSlide == 38){
		// Q4: Welcher Gruppe gehören die von Ihnen genannten Personen an?
			
          document.getElementById("slide37").style.display = "block";		
          document.getElementById("sixBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";
          
		  
          var text = $("#slide37 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide37 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide37 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000);

          currSlide++;
        
		} else if(currSlide == 39){
          var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes
          nodes.forEach(function(n) {
            if (n.index > 0) {
              if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              } 
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;  
            nodes.forEach(function(n) {
              //TODO: q4 noch zu dd2 umändern -> entsprechende Zuordnung zur Datenbank im Handler am Ende
			  //Collect Data
			  if (n.index > 0) {
                if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p3_dd2[i] = n.dd2 = "gar nicht";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y) {
                  p3_dd2[i] = n.dd2 = "einmal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y) {
                  p3_dd2[i] = n.dd2 = "zweimal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y) {
                  p3_dd2[i] = n.dd2 = "einmal im Monat";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p3_dd2[i] = n.dd2 = "einmal in der Woche";
                } else if (n.y > boxbar_offset_y) {
                  p3_dd2[i] = n.dd2 = "mehrmals pro Woche";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
			
			//TODO: die nächsten Slides werden erstmal übersprungen
			currSlide++;
			
			document.getElementById("slide37").style.display = "none";		
			document.getElementById("sixBar").style.display = "none";
			document.getElementById("labelBar1").style.display = "none";
			document.getElementById("slide7").style.display = "block";
		  }
//Token2		  
		  
	} else if(currSlide == 40){	  
		document.getElementById("slide7").style.display = "none"; 
		// Fix nodes in preparation for individual questions
		d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
		d3.selectAll(".node").style("display", "block");
				
		// Part 3 Question 1 Friend 1		
		askedAbout++;
		d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
		currNode = nodes[askedAbout];
		
		d3.select("svg").append("rect")
          .attr("class", "q_window")
          .attr("id", "3_question1_window")
          .attr("rx", 2)
          .attr("ry", 2)
          .attr("width", q_window_width)
          .attr("height", q_window_height)
          .attr("x", currNode.x - q_window_width / 2)
          .attr("y", currNode.y - q_window_height / 2);

		d3.select("svg").append("rect")
		  .attr("class", "backdrop")
          .attr("id", "3_backdrop1")
          .attr("x", currNode.x - q_window_width / 2 - 110)
          .attr("y", currNode.y - 240)
          .attr("width", backdrop_width)
          .attr("height", 300);		  
	
        d3.select("svg").append("text")
          .attr("class", "slideText")
          .attr("id", "3_question1_text1")
          .attr("x", currNode.x - q_window_width / 2 - 100)
          .attr("dy", currNode.y - 202)
          .text("Während Ihrer arbeitsteiligen Zusammenarbeit, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
          .call(wrap2, backdrop_width - 20);
          //.style("display", "none");		  
		
		drawBox(currNode);
		console.log("aktuelle Slide: " + currSlide);
		artKommunikation.style.display = "block";
		
	 }else if (currSlide == 40.1){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p3_q1_n1_1 = nodes3[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p3_q1_n1_2= nodes3[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p3_q1_n1_3 = nodes3[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p3_q1_n1_4 = nodes3[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p3_q1_n1_5 = nodes3[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p3_q1_n1_6 = nodes3[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p3_q1_n1_7 = nodes3[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("3_backdrop1").style.display = "none";
			document.getElementById("3_question1_text1").style.display = "none";
			document.getElementById("3_question1_window").style.display = "none";
			refreshRadio();
	
			if (askedAbout == numFriends) {
				currSlide = 40.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {		
			// Part 3 Question 1 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question2_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question2_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Während Ihrer arbeitsteiligen Zusammenarbeit, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}
	 }else if (currSlide ==	40.2){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p3_q1_n2_1 = nodes3[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p3_q1_n2_2= nodes3[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p3_q1_n2_3 = nodes3[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p3_q1_n2_4 = nodes3[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p3_q1_n2_5 = nodes3[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p3_q1_n2_6 = nodes3[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p3_q1_n2_7 = nodes3[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("3_backdrop2").style.display = "none";
			document.getElementById("3_question2_text1").style.display = "none";
			document.getElementById("3_question2_window").style.display = "none";
			refreshRadio();
		    
			if (askedAbout == numFriends) {
				currSlide = 40.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 3 Question 1 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question3_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question3_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Während Ihrer arbeitsteiligen Zusammenarbeit, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 40.3;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	 }else if (currSlide ==	40.3){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p3_q1_n3_1 = nodes3[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p3_q1_n3_2= nodes3[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p3_q1_n3_3 = nodes3[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p3_q1_n3_4 = nodes3[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p3_q1_n3_5 = nodes3[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p3_q1_n3_6 = nodes3[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p3_q1_n3_7 = nodes3[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("3_backdrop3").style.display = "none";
			document.getElementById("3_question3_text1").style.display = "none";
			document.getElementById("3_question3_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 40.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {	
			// Part 3 Question 1 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question4_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question4_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Während Ihrer arbeitsteiligen Zusammenarbeit, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}
	 }else if(currSlide == 40.4){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p3_q1_n4_1 = nodes3[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p3_q1_n4_2= nodes3[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p3_q1_n4_3 = nodes3[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p3_q1_n4_4 = nodes3[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p3_q1_n4_5 = nodes3[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p3_q1_n4_6 = nodes3[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p3_q1_n4_7 = nodes3[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("3_backdrop4").style.display = "none";
			document.getElementById("3_question4_text1").style.display = "none";
			document.getElementById("3_question4_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 40.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 3 Question 1 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question5_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question5_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Während Ihrer arbeitsteiligen Zusammenarbeit, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}			
	 }else if(currSlide == 40.5){
		var kommunikationForm = document.getElementById("artDerKommunikation");		
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
			
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p3_q1_n5_1 = nodes3[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p3_q1_n5_2= nodes3[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p3_q1_n5_3 = nodes3[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p3_q1_n5_4 = nodes3[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p3_q1_n5_5 = nodes3[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p3_q1_n5_6 = nodes3[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p3_q1_n5_7 = nodes3[askedAbout].q1_7 = "anders";
              }
				
				document.getElementById("3_backdrop5").style.display = "none";
				document.getElementById("3_question5_text1").style.display = "none";
				document.getElementById("3_question5_window").style.display = "none";
				artKommunikation.style.display = "none";
				refreshRadio();
			}
			
			// Part 3 Question 2 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question1_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question1_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' zusammengearbeitet haben.")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			currSlide = 40.6;
			console.log("aktuelle Slide: " + currSlide);
			themaErhalteneInfos.style.display = "block";
		}
		
	}else if (currSlide == 40.6){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p3_q2_n1 = nodes3[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop2_1").style.display = "none";
			document.getElementById("3_question1_text2").style.display = "none";
			document.getElementById("3_question1_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 41;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 3 Question 2 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question2_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2_2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question2_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' zusammengearbeitet haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	} else if (currSlide == 40.7){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p3_q2_n2 = nodes3[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop2_2").style.display = "none";
			document.getElementById("3_question2_text2").style.display = "none";
			document.getElementById("3_question2_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 41;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 3 Question 2 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question3_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2_3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question3_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' zusammengearbeitet haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 40.8;
			console.log("aktuelle Slide: " + currSlide);
				
			
			}
		}
	}else if (currSlide == 40.8){
// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p3_q2_n3 = nodes3[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop2_3").style.display = "none";
			document.getElementById("3_question3_text2").style.display = "none";
			document.getElementById("3_question3_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 41;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 3 Question 2 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question4_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2_4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question4_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' zusammengearbeitet haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 40.9){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
				
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p3_q2_n4 = nodes3[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop2_4").style.display = "none";
			document.getElementById("3_question4_text2").style.display = "none";
			document.getElementById("3_question4_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 41;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 3 Question 2 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question5_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop2_5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question5_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' zusammengearbeitet haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 41){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p3_q2_n5 = nodes3[askedAbout].q2 = erhInfoInput.value;
              }
				
				document.getElementById("3_backdrop2_5").style.display = "none";
				document.getElementById("3_question5_text2").style.display = "none";
				document.getElementById("3_question5_window2").style.display = "none";
				themaErhalteneInfos.style.display = "none";
				refreshRadio();
			}
			
			// Part 3 Question 3 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question1_window3")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop3_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question1_text3")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lässt sich die Zusammenarbeit an Maßnahmen oder Strategien hauptsächlich zuordnen?")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			digiSchulentwicklung.style.display = "block";
		}
		
	}else if(currSlide ==41.1){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes3[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes3[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes3[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes3[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes3[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes3[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("3_backdrop3_1").style.display = "none";
			document.getElementById("3_question1_text3").style.display = "none";
			document.getElementById("3_question1_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 41.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 3 Question 3 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question2_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop3_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question2_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lässt sich die Zusammenarbeit an Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 41.2){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes3[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes3[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes3[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes3[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes3[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes3[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("3_backdrop3_2").style.display = "none";
			document.getElementById("3_question2_text3").style.display = "none";
			document.getElementById("3_question2_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 41.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{		
				// Part 3 Question 3 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question3_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop3_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question3_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lässt sich die Zusammenarbeit an Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 41.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide==41.3){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes3[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes3[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes3[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes3[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes3[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes3[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("3_backdrop3_3").style.display = "none";
			document.getElementById("3_question3_text3").style.display = "none";
			document.getElementById("3_question3_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 41.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{			
				// Part 3 Question 3 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question4_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop3_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question4_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lässt sich die Zusammenarbeit an Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if(currSlide == 41.4){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes3[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes3[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes3[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes3[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes3[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes3[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("3_backdrop3_4").style.display = "none";
			document.getElementById("3_question4_text3").style.display = "none";
			document.getElementById("3_question4_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 41.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 3 Question 3 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question5_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop3_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question5_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihre Zusammenarbeit mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lässt sich die Zusammenarbeit an Maßnahmen oder Strategien hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 41.5){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;			
				//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes3[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes3[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes3[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes3[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes3[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes3[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
				document.getElementById("3_backdrop3_5").style.display = "none";
				document.getElementById("3_question5_text3").style.display = "none";
				document.getElementById("3_question5_window3").style.display = "none";
				digiSchulentwicklung.style.display = "none";
				refreshRadio();
			}
			
			// Part 3 Question 4 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question1_window4")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop4_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 500);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question1_text4")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			funktionenPersonen.style.display = "block";
		}
		
	}else if(currSlide == 41.6){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p3_q4_n1_1 = nodes3[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p3_q4_n1_2= nodes3[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p3_q4_n1_3 = nodes3[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p3_q4_n1_4 = nodes3[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p3_q4_n1_5 = nodes3[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p3_q4_n1_6 = nodes3[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p3_q4_n1_7 = nodes3[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p3_q4_n1_8= nodes3[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p3_q4_n1_9 = nodes3[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p3_q4_n1_10 = nodes3[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p3_q4_n1_11 = nodes3[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p3_q4_n1_12 = nodes3[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p3_q4_n1_13 = nodes3[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop4_1").style.display = "none";
			document.getElementById("3_question1_text4").style.display = "none";
			document.getElementById("3_question1_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{		
				// Part 3 Question 4 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question2_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop4_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question2_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 41.7){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p3_q4_n2_1 = nodes3[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p3_q4_n2_2= nodes3[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p3_q4_n2_3 = nodes3[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p3_q4_n2_4 = nodes3[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p3_q4_n2_5 = nodes3[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p3_q4_n2_6 = nodes3[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p3_q4_n2_7 = nodes3[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p3_q4_n2_8= nodes3[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p3_q4_n2_9 = nodes3[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p3_q4_n2_10 = nodes3[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p3_q4_n2_11 = nodes3[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p3_q4_n2_12 = nodes3[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p3_q4_n2_13 = nodes3[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop4_2").style.display = "none";
			document.getElementById("3_question2_text4").style.display = "none";
			document.getElementById("3_question2_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{			
				// Part 3 Question 4 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question3_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop4_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question3_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 41.8;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 41.8){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p3_q4_n3_1 = nodes3[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p3_q4_n3_2= nodes3[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p3_q4_n3_3 = nodes3[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p3_q4_n3_4 = nodes3[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p3_q4_n3_5 = nodes3[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p3_q4_n3_6 = nodes3[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p3_q4_n3_7 = nodes3[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p3_q4_n3_8= nodes3[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p3_q4_n3_9 = nodes3[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p3_q4_n3_10 = nodes3[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p3_q4_n3_11 = nodes3[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p3_q4_n3_12 = nodes3[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p3_q4_n3_13 = nodes3[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop4_3").style.display = "none";
			document.getElementById("3_question3_text4").style.display = "none";
			document.getElementById("3_question3_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{			
				// Part 3 Question 4 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question4_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop4_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question4_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 41.9){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p3_q4_n4_1 = nodes3[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p3_q4_n4_2= nodes3[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p3_q4_n4_3 = nodes3[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p3_q4_n4_4 = nodes3[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p3_q4_n4_5 = nodes3[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p3_q4_n4_6 = nodes3[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p3_q4_n4_7 = nodes3[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p3_q4_n4_8= nodes3[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p3_q4_n4_9 = nodes3[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p3_q4_n4_10 = nodes3[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p3_q4_n4_11 = nodes3[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p3_q4_n4_12 = nodes3[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p3_q4_n4_13 = nodes3[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("3_backdrop4_4").style.display = "none";
			document.getElementById("3_question4_text4").style.display = "none";
			document.getElementById("3_question4_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{			
				// Part 3 Question 4 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question5_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop4_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question5_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 42){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;			
			
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p3_q4_n5_1 = nodes3[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p3_q4_n5_2= nodes3[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p3_q4_n5_3 = nodes3[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p3_q4_n5_4 = nodes3[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p3_q4_n5_5 = nodes3[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p3_q4_n5_6 = nodes3[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p3_q4_n5_7 = nodes3[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p3_q4_n5_8= nodes3[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p3_q4_n5_9 = nodes3[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p3_q4_n5_10 = nodes3[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p3_q4_n5_11 = nodes3[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p3_q4_n5_12 = nodes3[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p3_q4_n5_13 = nodes3[askedAbout].q4_13 = inputFunktionen.value;
              }
				
				document.getElementById("3_backdrop4_5").style.display = "none";
				document.getElementById("3_question5_text4").style.display = "none";
				document.getElementById("3_question5_window4").style.display = "none";
				funktionenPersonen.style.display = "none";
				refreshRadio();
			}
			
			// Part 3 Question 5 Friend 1		
			askedAbout = 0;
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "3_question1_window5")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "3_backdrop5_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 250);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "3_question1_text5")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			wahrgenLernmöglichkeiten.style.display = "block";
		}
		
	}else if(currSlide == 42.1){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        } else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes3[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes3[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes3[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes3[askedAbout].q7 = "nein";
            }
			
			//console.log(inputValue);
			checked = false;
			document.getElementById("3_backdrop5_1").style.display = "none";
			document.getElementById("3_question1_text5").style.display = "none";
			document.getElementById("3_question1_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{			
				// Part 3 Question 5 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question2_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop5_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question2_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 42.2){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes3[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes3[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes3[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes3[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("3_backdrop5_2").style.display = "none";
			document.getElementById("3_question2_text5").style.display = "none";
			document.getElementById("3_question2_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 3 Question 5 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question3_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop5_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question3_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 42.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 42.3){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes3[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes3[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes3[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes3[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("3_backdrop5_3").style.display = "none";
			document.getElementById("3_question3_text5").style.display = "none";
			document.getElementById("3_question3_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 3 Question 5 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question4_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop5_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question4_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 42.4){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes3[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes3[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes3[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes3[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("3_backdrop5_4").style.display = "none";
			document.getElementById("3_question4_text5").style.display = "none";
			document.getElementById("3_question4_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 42.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 3 Question 5 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "3_question5_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "3_backdrop5_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "3_question5_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}	
		
	}else if(currSlide == 42.5){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
        }else {
			if(!skipped){			
				//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes3[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes3[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes3[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes3[askedAbout].q7 = "nein";
            }
			
				skipped = false;
				checked = false;
				document.getElementById("3_backdrop5_5").style.display = "none";
				document.getElementById("3_question5_text5").style.display = "none";
				document.getElementById("3_question5_window5").style.display = "none";
				wahrgenLernmöglichkeiten.style.display = "none";
				refreshRadio();
				currSlide = 50;
				showNext();
				
			}else{
				skipped = false;
				checked = false;				
				refreshRadio();
				currSlide = 50;
				showNext();
			}
		}
		
//----------------------------------------------------------------------------------------------------
//-------Links between Persons that talk to each other about digitalisation---------------------------
//----------------------------------------------------------------------------------------------------		
	
	} else if(currSlide == 50){

		//Nodes fixieren 
			d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
			restart();
			
			 // Q5: Which of these people know each other?
            
			document.getElementById("slide7").style.display = "none";
            document.getElementById("slide50").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;
            
            if (numFriends < 2) {
              showNext();
            }
	}else if(currSlide == 51){
		var gradFormalisierung2 = document.getElementById("gradFormalisierung2");
		var formalisierungForm2 = document.getElementById("gradDerFormalisierung2");
			
			//vorherige Slide ausblenden
			document.getElementById("slide50").style.display = "none";
			
			//hides nodes and links,
			d3.selectAll(".link").attr("display", "none"); 
			d3.selectAll(".node").style("display", "none");
			
			//Fixierung lösen (notwendig?)
			d3.selectAll(".node").classed("fixed", function(d) {  
                if (d.index > 0 ) {
                  d.fixed = false
                }
              });		
			
			//Collect Data
			for (var i= 1; i <= numFriends; i++){
				p3_link[i] = nodes[i].friendsWith;
			}		
		  
		  gradFormalisierung2.style.left = string_l + "px";		
          gradFormalisierung2.style.top = string_t;				
          gradFormalisierung2.style.top = string_t;				
          gradFormalisierung2.style.display = "block";
		  
		  currSlide = 51.5;
		  
	}else if(currSlide == 51.5){
		var formalisierungForm2 = document.getElementById("gradDerFormalisierung2"); 
		if ($('input[name=gradDerFormalisierung2]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
		}else{
			//Collect Data
              if (formalisierungForm2[0].checked) {
               p3_q6_1 = nodes[0].q6_1 = "geschieht auf regelmäßiger Basis";
              } if (formalisierungForm2[1].checked) {
               p3_q6_2= nodes[0].q6_2 = "es gibt dafür eigens vorgesehene Arbeitszeiten/Treffen (außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)";
              } if (formalisierungForm2[2].checked) {
               p3_q6_3 = nodes[0].q6_3 = "es werden bestimmte Methoden und Formate genutzt";
              } if (formalisierungForm2[3].checked) {
               p3_q6_4 = nodes[0].q6_4 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (formalisierungForm2[4].checked) {
               p3_q6_5 = nodes[0].q6_5 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }	if (formalisierungForm2[5].checked) {
               p3_q6_6 = nodes[0].q6_6 = "wird von der Schulleitung gefördert";
              }	if (formalisierungForm2[6].checked) {
               p3_q6_7 = nodes[0].q6_7 = "wird von der Schulleitung angeordnet";
              }
			  
			document.getElementById("gradFormalisierung2").style.display = "none";
			checked = false;
			currSlide = 52;
			showNext();
		}

	} else if (currSlide == 52){
		
		var rahmenbedingungenInfoweitergabe2 = document.getElementById("rahmenbedingungenInfoweitergabe2");
		var rahmenbedingungenForm2 = document.getElementById("rahmenDerInfoweitergabe2");
		
          rahmenbedingungenInfoweitergabe2.style.left = string_l + "px";		
          rahmenbedingungenInfoweitergabe2.style.top = string_t;				
          rahmenbedingungenInfoweitergabe2.style.top = string_t;				
          rahmenbedingungenInfoweitergabe2.style.display = "block";
		  console.log("aktuelle Slide: " + currSlide);
		  
		currSlide = 52.5;		
	} else if (currSlide == 52.5){
		var rahmenbedingungenForm2 = document.getElementById("rahmenDerInfoweitergabe2");
		if ($('input[name=rahmenDerInfoweitergabe2]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
		}else{
			//Collect Data
              if (rahmenbedingungenForm2[0].checked) {
               p3_q6_1 = nodes[0].q6_1 = "geschieht auf regelmäßiger Basis";
              } if (rahmenbedingungenForm2[1].checked) {
               p3_q6_2= nodes[0].q6_2 = "es gibt dafür eigens vorgesehene Arbeitszeiten/Treffen (außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)";
              } if (rahmenbedingungenForm2[2].checked) {
               p3_q6_3 = nodes[0].q6_3 = "es werden bestimmte Methoden und Formate genutzt";
              } if (rahmenbedingungenForm2[3].checked) {
               p3_q6_4 = nodes[0].q6_4 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (rahmenbedingungenForm2[4].checked) {
               p3_q6_5 = nodes[0].q6_5 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }	if (rahmenbedingungenForm2[5].checked) {
               p3_q6_6 = nodes[0].q6_6 = "wird von der Schulleitung gefördert";
              }	if (rahmenbedingungenForm2[6].checked) {
               p3_q6_7 = nodes[0].q6_7 = "wird von der Schulleitung angeordnet";
              }
			  
			document.getElementById("rahmenbedingungenInfoweitergabe2").style.display = "none";
			document.getElementById("slide52").style.display = "block";
			checked = false;
			currSlide = 53;
		}		
		
//---------------------------4. Durchgang-------------------------------------------------------	
	
	  } else if(currSlide == 53){
		  //4. Namenseingabe
		  
		  document.getElementById("slide52").style.display ="none";
			
			checked = false;
			skipped = false;
			numFriends = 0;
			askedAbout = 0;
			resetGraph();
				
			d3.selectAll(".node").attr("display", "block");
			d3.selectAll(".node").on('mousedown.drag', function(d) {
			return d.index > 0 ? true : null;
			});
					
			document.getElementById("slide53").style.display = "block";
			document.getElementById("name_input").style.display = "block";
			document.getElementById("name_input").style.left = string_l + "px";
			
		currSlide++;  
	  } else if (currSlide == 54){
		  
		if (numFriends < 5 && checked == false) {
            checked = true;
            console.log("fewer than 5 friends")
            friendPromptNonresponse();
			
        } else if (numFriends == 0 && checked == true){
			//Skipps to the next question about Persons
			currSlide = 72;
			document.getElementById("slide53").style.display = "none";
			document.getElementById("name_input").style.display = "none";
			restart();
			showNext();
			
		} else {
			//Collect Data
			for(var i = 1; i<= numFriends; i++){
				p4_name[i] = nodes[i].name;
				console.log("name" +i+ ": " + p4_name[i]);
			}
			
			for(var i = 1; i<= numFriends; i++){
				nodes4[i] = nodes[i];
			}
			
            checked = false;
            document.getElementById("slide53").style.display = "none";
            document.getElementById("slide4").style.display = "block";
            
			//das hier notwendig?
			var text = $("#slide4 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide4 .numfri").text(text);
            
            document.getElementById("name_input").style.display = "none";
            currSlide++;
         }
		 
	  } else if (currSlide == 55){
		  document.getElementById("slide4").style.display = "none";
	  	  document.getElementById("slide5").style.display = "block";
		  document.getElementById("tenBar").style.display = "block";
		  document.getElementById("labelBar2").style.display = "block";
		  
          var text = $("#slide5 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide5 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide5 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000); 
          currSlide++;  
		  
	  } else if (currSlide == 56){
		var nodeAbove = false;
        var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes - shows NonResponse message if Nodes are not correctly placed
          //TODO: für tenBar entsprechend anpassen, da 1. und 8. Box neu angeordnet
		  nodes.forEach(function(n) {
            if (n.index > 0) {
              if (((n.x > boxbar_offset_x + bar6_target_width) && (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)) && (n.y < boxbar_offset_y)) || (n.y < (boxbar_offset_y - 450))){
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });
		  
          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;  
            nodes.forEach(function(n) {
              //TODO: abweichende Positionierung der 1. und 8. Box, boxbar_offset_y etc. entsprechend anpassen!
			  //Collect Data
			  if (n.index > 0) {
				if (n.x < boxbar_offset_x + bar6_target_width && n.y< (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*2) && (boxbar_offset_y - bar_label_height*3 - boxbar_label_margin*3 - boxbar_margin*3 - bar_target_height*3)) {
                  p4_dd1[i] = n.dd1 = "Mitglied meiner Schule"; 
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height) && n.y > (boxbar_offset_y - bar_label_height*2 - boxbar_label_margin*2 - boxbar_margin*2 - bar_target_height*2)) {
                  p4_dd1[i] = n.dd1 = "Mitglied anderer Netzwerkschule";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p4_dd1[i] = n.dd1 = "SchülerIn";
                } else if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p4_dd1[i] = n.dd1 = "externe Medienberatung";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y){
                  p4_dd1[i] = n.dd1 = "Schulträger";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y){
                  p4_dd1[i] = n.dd1 = "externe Fortbildungsakteure";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y){
                  p4_dd1[i] = n.dd1 = "Learning Lab";
				} else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p4_dd1[i] = n.dd1 = "Elternvertreter";
				} else if (n.y > boxbar_offset_y){
                  p4_dd1[i] = n.dd1 = "Bezirksregierung";
                } else if (n.x < (boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + bar6_target_width) && n.y < (boxbar_offset_y - bar_label_height - boxbar_label_margin) && n.y > (boxbar_offset_y - bar_label_height - boxbar_label_margin - 150)) {
                  p4_dd1[i] = n.dd1 = "Sonstiges";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
          
            document.getElementById("labelBar2").style.display = "none";
            document.getElementById("tenBar").style.display = "none";  	
            document.getElementById("slide5").style.display = "none";  
			currSlide++;
			showNext();
          }
		  
	  } else if (currSlide == 57){
		  document.getElementById("slide57").style.display = "block";		
          document.getElementById("sixBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";
          
		  
          var text = $("#slide57 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide37 .numfri1").text(text);

          var text = $("#slide5 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide37 .numfri2").text(text);              
          
          d3.selectAll(".node").attr("display", "block");  
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) { 
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");  
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000);

          currSlide++;
	  } else if (currSlide == 58){
		   var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes
          nodes.forEach(function(n) {
            if (n.index > 0) {
              if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              } 
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
			var i = 1;
            nodes.forEach(function(n) {
              //TODO: q4 noch zu dd2 umändern -> entsprechende Zuordnung zur Datenbank im Handler am Ende
			  //Collect Data
			  if (n.index > 0) {
                if (n.x < boxbar_offset_x + bar6_target_width && n.y > boxbar_offset_y) {
                  p4_dd2[i] = n.dd2 = "gar nicht";
                } else if (n.x < boxbar_offset_x + bar6_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y) {
                  p4_dd2[i] = n.dd2 = "einmal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + bar6_target_width && n.y > boxbar_offset_y) {
                  p4_dd2[i] = n.dd2 = "zweimal";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + bar6_target_width && n.y > boxbar_offset_y) {
                  p4_dd2[i] = n.dd2 = "einmal im Monat";
                } else if (n.x < boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + bar6_target_width && n.y > boxbar_offset_y){
                  p4_dd2[i] = n.dd2 = "einmal in der Woche";
                } else if (n.y > boxbar_offset_y) {
                  p4_dd2[i] = n.dd2 = "mehrmals pro Woche";
                }
				i++;
			}
            }); 

            checked = false;
                  
            d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();
			
			currSlide = 60;
			
			document.getElementById("slide57").style.display = "none";		
			document.getElementById("sixBar").style.display = "none";
			document.getElementById("labelBar1").style.display = "none";
			document.getElementById("slide7").style.display = "block";
		  }
		  
//Token3
	}else if (currSlide == 60){
		document.getElementById("slide7").style.display = "none"; 
		// Fix nodes in preparation for individual questions
		d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
		d3.selectAll(".node").style("display", "block");
			
		// Part 4 Question 1 Friend 1		
		askedAbout++;
		d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
		currNode = nodes[askedAbout];
		
		d3.select("svg").append("rect")
          .attr("class", "q_window")
          .attr("id", "4_question1_window")
          .attr("rx", 2)
          .attr("ry", 2)
          .attr("width", q_window_width)
          .attr("height", q_window_height)
          .attr("x", currNode.x - q_window_width / 2)
          .attr("y", currNode.y - q_window_height / 2);

		d3.select("svg").append("rect")
		  .attr("class", "backdrop")
          .attr("id", "4_backdrop1")
          .attr("x", currNode.x - q_window_width / 2 - 110)
          .attr("y", currNode.y - 240)
          .attr("width", backdrop_width)
          .attr("height", 300);		  
	
        d3.select("svg").append("text")
          .attr("class", "slideText")
          .attr("id", "4_question1_text1")
          .attr("x", currNode.x - q_window_width / 2 - 100)
          .attr("dy", currNode.y - 202)
          .text("Bei Ihrer gemeinsamen, systematischen Entwicklung von Maßnahmen oder Strategien, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
          .call(wrap2, backdrop_width - 20);
          //.style("display", "none");		  
		
		drawBox(currNode);
		console.log("aktuelle Slide: " + currSlide);
		artKommunikation.style.display = "block";
		
	 }else if (currSlide == 60.1){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p4_q1_n1_1 = nodes4[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p4_q1_n1_2= nodes4[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p4_q1_n1_3 = nodes4[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p4_q1_n1_4 = nodes4[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p4_q1_n1_5 = nodes4[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p4_q1_n1_6 = nodes4[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p4_q1_n1_7 = nodes4[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("4_backdrop1").style.display = "none";
			document.getElementById("4_question1_text1").style.display = "none";
			document.getElementById("4_question1_window").style.display = "none";
			refreshRadio();
	
			if (askedAbout == numFriends) {
				currSlide = 60.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {		
			// Part 4 Question 1 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question2_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question2_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Bei Ihrer gemeinsamen, systematischen Entwicklung von Maßnahmen oder Strategien, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}
	 }else if (currSlide ==	60.2){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p4_q1_n2_1 = nodes4[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p4_q1_n2_2= nodes4[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p4_q1_n2_3 = nodes4[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p4_q1_n2_4 = nodes4[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p4_q1_n2_5 = nodes4[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p4_q1_n2_6 = nodes4[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p4_q1_n2_7 = nodes4[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("4_backdrop2").style.display = "none";
			document.getElementById("4_question2_text1").style.display = "none";
			document.getElementById("4_question2_window").style.display = "none";
			refreshRadio();
		    
			if (askedAbout == numFriends) {
				currSlide = 60.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 4 Question 1 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question3_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question3_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Bei Ihrer gemeinsamen, systematischen Entwicklung von Maßnahmen oder Strategien, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 60.3;
			console.log("aktuelle Slide: " + currSlide);
			}
		}
	 }else if (currSlide ==	60.3){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p4_q1_n3_1 = nodes4[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p4_q1_n3_2= nodes4[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p4_q1_n3_3 = nodes4[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p4_q1_n3_4 = nodes4[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p4_q1_n3_5 = nodes4[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p4_q1_n3_6 = nodes4[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p4_q1_n3_7 = nodes4[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("4_backdrop3").style.display = "none";
			document.getElementById("4_question3_text1").style.display = "none";
			document.getElementById("4_question3_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 60.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 4 Question 1 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question4_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question4_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Bei Ihrer gemeinsamen, systematischen Entwicklung von Maßnahmen oder Strategien, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}
	 }else if(currSlide == 60.4){
		var kommunikationForm = document.getElementById("artDerKommunikation");
		// If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p4_q1_n4_1 = nodes4[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p4_q1_n4_2= nodes4[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p4_q1_n4_3 = nodes4[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p4_q1_n4_4 = nodes4[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p4_q1_n4_5 = nodes4[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p4_q1_n4_6 = nodes4[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p4_q1_n4_7 = nodes4[askedAbout].q1_7 = "anders";
              }
			
			checked = false;
			document.getElementById("4_backdrop4").style.display = "none";
			document.getElementById("4_question4_text1").style.display = "none";
			document.getElementById("4_question4_window").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends) {
				currSlide = 60.5;
				artKommunikation.style.display = "none";
				skipQuestions();
					
			} else {
			// Part 4 Question 1 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question5_window")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question5_text1")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Bei Ihrer gemeinsamen, systematischen Entwicklung von Maßnahmen oder Strategien, wie haben Sie mit '" + nodes[askedAbout].name + "' überwiegend kommuniziert?(Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
				
			}
		}			
	 }else if(currSlide == 60.5){
		 var kommunikationForm = document.getElementById("artDerKommunikation");
		 // If user has not selected an option, alert with popup
		if ($('input[name=artDerKommunikation]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else { 
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data!
              if (kommunikationForm[0].checked) {
               p4_q1_n5_1 = nodes4[askedAbout].q1_1 = "persönliches Gespräch";
              } if (kommunikationForm[1].checked) {
               p4_q1_n5_2= nodes4[askedAbout].q1_2 = "E-Mail";
              } if (kommunikationForm[2].checked) {
               p4_q1_n5_3 = nodes4[askedAbout].q1_3 = "Schulische digitale Plattform";
              } if (kommunikationForm[3].checked) {
               p4_q1_n5_4 = nodes4[askedAbout].q1_4 = "soziale Medien/ Onlineplattform";
              }	if (kommunikationForm[4].checked) {
               p4_q1_n5_5 = nodes4[askedAbout].q1_5 = "Messenger (WhatsApp, Threema, o.ä.)";
              }	if (kommunikationForm[5].checked) {
               p4_q1_n5_6 = nodes4[askedAbout].q1_6 = "Telefon";
              }	if (kommunikationForm[6].checked) {
               p4_q1_n5_7 = nodes4[askedAbout].q1_7 = "anders";
              }
				
				document.getElementById("4_backdrop5").style.display = "none";
				document.getElementById("4_question5_text1").style.display = "none";
				document.getElementById("4_question5_window").style.display = "none";
				artKommunikation.style.display = "none";
				refreshRadio();
			}
			
			// Part 4 Question 2 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question1_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question1_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' Strategien oder Maßnahmen entwickelt haben.")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			currSlide = 60.6;
			console.log("aktuelle Slide: " + currSlide);
			themaErhalteneInfos.style.display = "block";
		}
	}else if (currSlide == 60.6){
		// If user has not selected an option, alert with popup		
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p4_q2_n1 = nodes4[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop2_1").style.display = "none";
			document.getElementById("4_question1_text2").style.display = "none";
			document.getElementById("4_question1_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 61;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 4 Question 2 Friend 2
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question2_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2_2")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question2_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' Strategien oder Maßnahmen entwickelt haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	} else if (currSlide == 60.7){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p4_q2_n2 = nodes4[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop2_2").style.display = "none";
			document.getElementById("4_question2_text2").style.display = "none";
			document.getElementById("4_question2_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 61;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 4 Question 2 Friend 3
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question3_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2_3")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question3_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' Strategien oder Maßnahmen entwickelt haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			currSlide = 60.8;
			console.log("aktuelle Slide: " + currSlide);
				
			
			}
		}
	}else if (currSlide == 60.8){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p4_q2_n3 = nodes4[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop2_3").style.display = "none";
			document.getElementById("4_question3_text2").style.display = "none";
			document.getElementById("4_question3_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 61;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {		
			// Part 4 Question 2 Friend 4
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question4_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2_4")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question4_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' Strategien oder Maßnahmen entwickelt haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 60.9){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked) {
            promptNonresponse();
            checked = true;
			
        } else {
			//TODO: collect Data!
			if (erhInfoInput.value != null) {
               p4_q2_n4 = nodes4[askedAbout].q2 = erhInfoInput.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop2_4").style.display = "none";
			document.getElementById("4_question4_text2").style.display = "none";
			document.getElementById("4_question4_window2").style.display = "none";
			refreshRadio();
	
		if (askedAbout == numFriends){
			currSlide = 61;
			themaErhalteneInfos.style.display = "none";
			skipQuestions();
			
		} else {	
			// Part 4 Question 2 Friend 5
			 askedAbout++;
			 currNode = nodes[askedAbout];
			 d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			 
			 d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question5_window2")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop2_5")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 200);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question5_text2")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Bitte beschreiben Sie in einigen Stichworten, zu welchem Thema Sie mit '" + nodes[askedAbout].name + "' Strategien oder Maßnahmen entwickelt haben.")
			  .call(wrap2, backdrop_width - 20);
			
			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			
			}
		}
	}else if (currSlide == 61){
		// If user has not selected an option, alert with popup
		if ((erhInfoInput.value === null || erhInfoInput.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        } else {
			if(!skipped){
				skipped = false;
				checked = false;
				
			//TODO: collect Data! 
			if (erhInfoInput.value != null) {
               p4_q2_n5 = nodes4[askedAbout].q2 = erhInfoInput.value;
              }
				
				document.getElementById("4_backdrop2_5").style.display = "none";
				document.getElementById("4_question5_text2").style.display = "none";
				document.getElementById("4_question5_window2").style.display = "none";
				themaErhalteneInfos.style.display = "none";
				refreshRadio();
			}
			
			// Part 4 Question 3 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question1_window3")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop3_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 300);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question1_text3")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Maßnahmen oder Strategien, die Sie mit '" + nodes[askedAbout].name + "' entwickelt haben hauptsächlich zuordnen?")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			digiSchulentwicklung.style.display = "block";
		}
		
	}else if(currSlide ==61.1){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes4[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes4[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes4[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes4[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes4[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes4[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("4_backdrop3_1").style.display = "none";
			document.getElementById("4_question1_text3").style.display = "none";
			document.getElementById("4_question1_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 61.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				// Part 4 Question 3 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question2_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop3_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question2_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Maßnahmen oder Strategien, die Sie mit '" + nodes[askedAbout].name + "' entwickelt haben hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 61.2){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes4[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes4[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes4[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes4[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes4[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes4[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("4_backdrop3_2").style.display = "none";
			document.getElementById("4_question2_text3").style.display = "none";
			document.getElementById("4_question2_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 61.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{			
				// Part 4 Question 3 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question3_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop3_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question3_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Maßnahmen oder Strategien, die Sie mit '" + nodes[askedAbout].name + "' entwickelt haben hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 61.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide==61.3){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes4[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes4[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes4[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes4[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes4[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes4[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("4_backdrop3_3").style.display = "none";
			document.getElementById("4_question3_text3").style.display = "none";
			document.getElementById("4_question3_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 61.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{			
				// Part 4 Question 3 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question4_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop3_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question4_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Maßnahmen oder Strategien, die Sie mit '" + nodes[askedAbout].name + "' entwickelt haben hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if(currSlide == 61.4){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		
	// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
        }else { 
			//TODO: collect Data!
			if (schulentwicklungForm[0].checked) {
				nodes4[askedAbout].q7 = "Technische Ausstattung der Schule";
            } else if (schulentwicklungForm[1].checked) {
                nodes4[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
            } else if (schulentwicklungForm[2].checked) {
                nodes4[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
            } else if (schulentwicklungForm[3].checked) {
                nodes4[askedAbout].q7 = "Organisatorische Bedingungen";
            } else if (schulentwicklungForm[4].checked) {
                nodes4[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
            } else if (schulentwicklungForm[5].checked) {
                nodes4[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
			}		
			
			checked = false;
			document.getElementById("4_backdrop3_4").style.display = "none";
			document.getElementById("4_question4_text3").style.display = "none";
			document.getElementById("4_question4_window3").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 61.5;
				digiSchulentwicklung.style.display = "none";
				skipQuestions();
					
			}else{
				//Part 4 Question 3 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question5_window3")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop3_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 300);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question5_text3")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über Ihren Austausch mit '" + nodes[askedAbout].name + "' erfahren. Welchem Bereich lassen sich die Maßnahmen oder Strategien, die Sie mit '" + nodes[askedAbout].name + "' entwickelt haben hauptsächlich zuordnen?")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
	}else if (currSlide == 61.5){
		var schulentwicklungForm = document.getElementById("digitaleSchulentwicklung");
		// If user has not selected an option, alert with popup
		if ($('input[name=digitaleSchulentwicklung]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else {
			if(!skipped){
				skipped = false;
				checked = false;			
					//TODO: collect Data! 
				if (schulentwicklungForm[0].checked) {
					nodes4[askedAbout].q7 = "Technische Ausstattung der Schule";
				} else if (schulentwicklungForm[1].checked) {
					nodes4[askedAbout].q7 = "Fortbildungen/ Schulungen für Lehrkräfte";
				} else if (schulentwicklungForm[2].checked) {
					nodes4[askedAbout].q7 = "Unterrichtsentwicklung mit digitalen Medien";
				} else if (schulentwicklungForm[3].checked) {
					nodes4[askedAbout].q7 = "Organisatorische Bedingungen";
				} else if (schulentwicklungForm[4].checked) {
					nodes4[askedAbout].q7 = "Kommunikation (Öffentlichkeitsarbeiten, Elternarbeit, Schülerpartizipation)";
				} else if (schulentwicklungForm[5].checked) {
					nodes4[askedAbout].q7 = "Gesamtstrategien Digitalisierung (z.B. Medienkonzept)";
				}		
		
				document.getElementById("4_backdrop3_5").style.display = "none";
				document.getElementById("4_question5_text3").style.display = "none";
				document.getElementById("4_question5_window3").style.display = "none";
				digiSchulentwicklung.style.display = "none";
				refreshRadio();
			}
			
			// Part 4 Question 4 Friend 1		
			askedAbout = 0;				//auf 0 zurücksetzen, damit die Skip-Funktion genutzt werden kann
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question1_window4")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop4_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 500);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question1_text4")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			funktionenPersonen.style.display = "block";
		}
		
	}else if(currSlide == 61.6){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p4_q4_n1_1 = nodes4[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p4_q4_n1_2= nodes4[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p4_q4_n1_3 = nodes4[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p4_q4_n1_4 = nodes4[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p4_q4_n1_5 = nodes4[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p4_q4_n1_6 = nodes4[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p4_q4_n1_7 = nodes4[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p4_q4_n1_8= nodes4[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p4_q4_n1_9 = nodes4[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p4_q4_n1_10 = nodes4[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p4_q4_n1_11 = nodes4[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p4_q4_n1_12 = nodes4[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p4_q4_n1_13 = nodes4[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop4_1").style.display = "none";
			document.getElementById("4_question1_text4").style.display = "none";
			document.getElementById("4_question1_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{			
				// Part 4 Question 4 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question2_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop4_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question2_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 61.7){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p4_q4_n2_1 = nodes4[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p4_q4_n2_2= nodes4[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p4_q4_n2_3 = nodes4[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p4_q4_n2_4 = nodes4[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p4_q4_n2_5 = nodes4[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p4_q4_n2_6 = nodes4[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p4_q4_n2_7 = nodes4[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p4_q4_n2_8= nodes4[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p4_q4_n2_9 = nodes4[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p4_q4_n2_10 = nodes4[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p4_q4_n2_11 = nodes4[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p4_q4_n2_12 = nodes4[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p4_q4_n2_13 = nodes4[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop4_2").style.display = "none";
			document.getElementById("4_question2_text4").style.display = "none";
			document.getElementById("4_question2_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{				
				// Part 4 Question 4 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question3_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop4_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question3_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 61.8;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 61.8){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p4_q4_n3_1 = nodes4[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p4_q4_n3_2= nodes4[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p4_q4_n3_3 = nodes4[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p4_q4_n3_4 = nodes4[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p4_q4_n3_5 = nodes4[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p4_q4_n3_6 = nodes4[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p4_q4_n3_7 = nodes4[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p4_q4_n3_8= nodes4[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p4_q4_n3_9 = nodes4[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p4_q4_n3_10 = nodes4[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p4_q4_n3_11 = nodes4[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p4_q4_n3_12 = nodes4[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p4_q4_n3_13 = nodes4[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop4_3").style.display = "none";
			document.getElementById("4_question3_text4").style.display = "none";
			document.getElementById("4_question3_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{	
				// Part 4 Question 4 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question4_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop4_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question4_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 61.9){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked) {
            promptNonresponse();
            checked = true;

        }else {
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p4_q4_n4_1 = nodes4[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p4_q4_n4_2= nodes4[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p4_q4_n4_3 = nodes4[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p4_q4_n4_4 = nodes4[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p4_q4_n4_5 = nodes4[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p4_q4_n4_6 = nodes4[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p4_q4_n4_7 = nodes4[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p4_q4_n4_8= nodes4[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p4_q4_n4_9 = nodes4[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p4_q4_n4_10 = nodes4[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p4_q4_n4_11 = nodes4[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p4_q4_n4_12 = nodes4[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p4_q4_n4_13 = nodes4[askedAbout].q4_13 = inputFunktionen.value;
              }
			
			checked = false;
			document.getElementById("4_backdrop4_4").style.display = "none";
			document.getElementById("4_question4_text4").style.display = "none";
			document.getElementById("4_question4_window4").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62;
				funktionenPersonen.style.display = "none";
				skipQuestions();
								
			}else{				
				// Part 4 Question 4 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question5_window4")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop4_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 500);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question5_text4")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("In welchen der folgenden Funktionen ist '" + nodes[askedAbout].name + "' tätig? (Mehrfachnennungen möglich)")
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 62){
		var funktionenForm = document.getElementById("funktionenDerPersonen");
		// If user has not selected an option, alert with popup
		if ($('input[name=funktionenDerPersonen]:checked').length == 0 && (inputFunktionen.value === null || inputFunktionen.value === "") && !checked && !skipped) {
            promptNonresponse();
            checked = true;
			
        }else { 
			if(!skipped){
				skipped = false;
				checked = false;
			
			//TODO: collect Data!
              if (funktionenForm[0].checked) {
               p4_q4_n5_1 = nodes4[askedAbout].q4_1 = "(erweiterte) Schulleitung";
              } if (funktionenForm[1].checked) {
               p4_q4_n5_2= nodes4[askedAbout].q4_2 = "Didaktische Leitung";
              } if (funktionenForm[2].checked) {
               p4_q4_n5_3 = nodes4[askedAbout].q4_3 = "Steuergruppe oder AG Schulentwicklung";
              } if (funktionenForm[3].checked) {
               p4_q4_n5_4 = nodes4[askedAbout].q4_4 = "AG digitale Medien, Medienkonzept oder ähnliches";
              }	if (funktionenForm[4].checked) {
               p4_q4_n5_5 = nodes4[askedAbout].q4_5 = "Medienbeauftragte*r/ IT-Koordinato*r";
              }	if (funktionenForm[5].checked) {
               p4_q4_n5_6 = nodes4[askedAbout].q4_6 = "Vorsitz der Fachkonferenz";
              }	if (funktionenForm[6].checked) {
               p4_q4_n5_7 = nodes4[askedAbout].q4_7 = "Mitglied der Schulkonferenz";
              }if (funktionenForm[7].checked) {
               p4_q4_n5_8= nodes4[askedAbout].q4_8 = "Fortbildungsbeauftragte*r";
              } if (funktionenForm[8].checked) {
               p4_q4_n5_9 = nodes4[askedAbout].q4_9 = "Mitglied des Lehrrates";
              } if (funktionenForm[9].checked) {
               p4_q4_n5_10 = nodes4[askedAbout].q4_10 = "Schülervertretung (inkl. Medienscouts)";
              }	if (funktionenForm[10].checked) {
               p4_q4_n5_11 = nodes4[askedAbout].q4_11 = "Nicht-schulisch (z.B. Schulträger, Universität, Beratung oder ähnliches)";
              }	if (funktionenForm[11].checked) {
               p4_q4_n5_12 = nodes4[askedAbout].q4_12 = "weiß ich nicht";
              }	if (funktionenForm[12].checked) {
               p4_q4_n5_13 = nodes4[askedAbout].q4_13 = inputFunktionen.value;
              }
				
				document.getElementById("4_backdrop4_5").style.display = "none";
				document.getElementById("4_question5_text4").style.display = "none";
				document.getElementById("4_question5_window4").style.display = "none";
				funktionenPersonen.style.display = "none";
				refreshRadio();
			}
				
			// Part 4 Question 5 Friend 1		
			askedAbout = 0;
			askedAbout++;
			d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
			currNode = nodes[askedAbout];
			
			
			d3.select("svg").append("rect")
			  .attr("class", "q_window")
			  .attr("id", "4_question1_window5")
			  .attr("rx", 2)
			  .attr("ry", 2)
			  .attr("width", q_window_width)
			  .attr("height", q_window_height)
			  .attr("x", currNode.x - q_window_width / 2)
			  .attr("y", currNode.y - q_window_height / 2);

			d3.select("svg").append("rect")
			  .attr("class", "backdrop")
			  .attr("id", "4_backdrop5_1")
			  .attr("x", currNode.x - q_window_width / 2 - 110)
			  .attr("y", currNode.y - 240)
			  .attr("width", backdrop_width)
			  .attr("height", 250);		  
		
			d3.select("svg").append("text")
			  .attr("class", "slideText")
			  .attr("id", "4_question1_text5")
			  .attr("x", currNode.x - q_window_width / 2 - 100)
			  .attr("dy", currNode.y - 202)
			  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
			  .call(wrap2, backdrop_width - 20);

			drawBox(currNode);
			console.log("aktuelle Slide: " + currSlide);
			wahrgenLernmöglichkeiten.style.display = "block";
		}
		
	}else if(currSlide == 62.1){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        } else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes4[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes4[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes4[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes4[askedAbout].q7 = "nein";
            }
			
			//console.log(inputValue);
			checked = false;
			document.getElementById("4_backdrop5_1").style.display = "none";
			document.getElementById("4_question1_text5").style.display = "none";
			document.getElementById("4_question1_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{
				// Part 4 Question 5 Friend 2		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question2_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop5_2")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question2_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 62.2){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes4[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes4[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes4[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes4[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("4_backdrop5_2").style.display = "none";
			document.getElementById("4_question2_text5").style.display = "none";
			document.getElementById("4_question2_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{			
				// Part 4 Question 5 Friend 3		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question3_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop5_3")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question3_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				currSlide = 62.3;
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 62.3){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes4[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes4[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes4[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes4[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("4_backdrop5_3").style.display = "none";
			document.getElementById("4_question3_text5").style.display = "none";
			document.getElementById("4_question3_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{			
				// Part 4 Question 5 Friend 4		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question4_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop5_4")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question4_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}
		
	}else if(currSlide == 62.4){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
			
        }else { 
			//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes4[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes4[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes4[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes4[askedAbout].q7 = "nein";
            }
			
			checked = false;
			document.getElementById("4_backdrop5_4").style.display = "none";
			document.getElementById("4_question4_text5").style.display = "none";
			document.getElementById("4_question4_window5").style.display = "none";
			refreshRadio();
			
			if (askedAbout == numFriends){
				currSlide = 62.5;
				wahrgenLernmöglichkeiten.style.display = "none";
				skipQuestions();
				
			}else{			
				// Part 4 Question 5 Friend 5		
				askedAbout++;
				d3.selectAll(".node").attr("opacity", function(d) { return d.index == askedAbout ? 1 : .4 });
				currNode = nodes[askedAbout];
				
				
				d3.select("svg").append("rect")
				  .attr("class", "q_window")
				  .attr("id", "4_question5_window5")
				  .attr("rx", 2)
				  .attr("ry", 2)
				  .attr("width", q_window_width)
				  .attr("height", q_window_height)
				  .attr("x", currNode.x - q_window_width / 2)
				  .attr("y", currNode.y - q_window_height / 2);

				d3.select("svg").append("rect")
				  .attr("class", "backdrop")
				  .attr("id", "4_backdrop5_5")
				  .attr("x", currNode.x - q_window_width / 2 - 110)
				  .attr("y", currNode.y - 240)
				  .attr("width", backdrop_width)
				  .attr("height", 250);		  
			
				d3.select("svg").append("text")
				  .attr("class", "slideText")
				  .attr("id", "4_question5_text5")
				  .attr("x", currNode.x - q_window_width / 2 - 100)
				  .attr("dy", currNode.y - 202)
				  .text("Wir würden gerne mehr über '" + nodes[askedAbout].name + "' erfahren. Haben Sie das Gefühl, viel von '"+ nodes[askedAbout].name +"' in Bezug auf die Nutzung digitaler Technologien lernen zu können?" )
				  .call(wrap2, backdrop_width - 20);

				drawBox(currNode);
				console.log("aktuelle Slide: " + currSlide);
			}
		}	
		
	}else if(currSlide == 62.5){
		var lernmöglichkeitenForm = document.getElementById("subjWahrLernm");
		// If user has not selected an option, alert with popup
		if ($('input[name=subjWahrLernm]:checked').length == 0 && !checked && !skipped) {
            promptNonresponse();
            checked = true;
        }else {
			if(!skipped){
				//TODO: collect Data!
			if (lernmöglichkeitenForm[0].checked) {
				nodes4[askedAbout].q7 = "ja";
            } else if (lernmöglichkeitenForm[1].checked) {
                nodes4[askedAbout].q7 = "eher ja";
            } else if (lernmöglichkeitenForm[2].checked) {
                nodes4[askedAbout].q7 = "eher nein";
            } else if (lernmöglichkeitenForm[3].checked) {
                nodes4[askedAbout].q7 = "nein";
            }
			
				skipped = false;
				checked = false;
				document.getElementById("4_backdrop5_5").style.display = "none";
				document.getElementById("4_question5_text5").style.display = "none";
				document.getElementById("4_question5_window5").style.display = "none";
				wahrgenLernmöglichkeiten.style.display = "none";
				refreshRadio();
				currSlide = 69;
				showNext();
				
			}else{
				skipped = false;
				checked = false;				
				refreshRadio();
				currSlide = 69;
				showNext();
			}
		}
		
	}else if (currSlide == 69){  
		document.getElementById("slide7").style.display = "none";
		
		//Links between Persons that talk to each other about digitalisation
		//Nodes fixieren 
			d3.selectAll(".node").classed("fixed", function(d) { 
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
			restart();
			
			 // Q5: Which of these people know each other?
            
			document.getElementById("slide7").style.display = "none";
            document.getElementById("slide69").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;
            
            if (numFriends < 2) {
              showNext();
            }
			
	}else if (currSlide == 70){
		var gradFormalisierung3 = document.getElementById("gradFormalisierung3");
		var formalisierungForm3 = document.getElementById("gradDerFormalisierung3");
			
			//vorherige Slide ausblenden
			document.getElementById("slide69").style.display = "none";
			
			//hides nodes and links,
			d3.selectAll(".link").attr("display", "none"); 
			d3.selectAll(".node").style("display", "none");
			
			//Fixierung lösen (notwendig?)
			d3.selectAll(".node").classed("fixed", function(d) {  
                if (d.index > 0 ) {
                  d.fixed = false
                }
              });
			
			//Collect Data
			for (var i= 1; i <= numFriends; i++){
				p4_link[i] = nodes[i].friendsWith;
			}			  
			  
		numFriends = 0;
		restart(); 

        gradFormalisierung3.style.left = string_l + "px";		
        gradFormalisierung3.style.top = string_t;				
        gradFormalisierung3.style.top = string_t;				
        gradFormalisierung3.style.display = "block";
		   
		currSlide++;

	} else if (currSlide == 71){
		var formalisierungForm3 = document.getElementById("gradDerFormalisierung3");
		if ($('input[name=gradDerFormalisierung3]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
		}else{
			//Collect Data
              if (formalisierungForm3[0].checked) {
               p4_q6_1 = nodes[0].q6_1 = "geschieht auf regelmäßiger Basis";
              } if (formalisierungForm3[1].checked) {
               p4_q6_2= nodes[0].q6_2 = "es gibt dafür vorgesehene Arbeitszeiten/Treffen (außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)";
              } if (formalisierungForm3[2].checked) {
               p4_q6_3 = nodes[0].q6_3 = "es werden bestimmte Methoden und Formate genutzt";
              } if (formalisierungForm3[3].checked) {
               p4_q6_4 = nodes[0].q6_4 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (formalisierungForm3[4].checked) {
               p4_q6_5 = nodes[0].q6_5 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }	if (formalisierungForm3[5].checked) {
               p4_q6_6 = nodes[0].q6_6 = "wird von der Schulleitung gefördert";
              }	if (formalisierungForm3[6].checked) {
               p4_q6_7 = nodes[0].q6_7 = "wird von der Schulleitung angeordnet";
              } 
			  
			document.getElementById("gradFormalisierung3").style.display = "none";
			checked = false;
			currSlide = 71.5;
			showNext();
		}
	} else if (currSlide == 71.5){
		
		var rahmenbedingungenInfoweitergabe3 = document.getElementById("rahmenbedingungenInfoweitergabe3");
		var rahmenbedingungenForm3 = document.getElementById("rahmenDerInfoweitergabe3");
		
          rahmenbedingungenInfoweitergabe3.style.left = string_l + "px";		
          rahmenbedingungenInfoweitergabe3.style.top = string_t;				
          rahmenbedingungenInfoweitergabe3.style.top = string_t;				
          rahmenbedingungenInfoweitergabe3.style.display = "block";
		  console.log("aktuelle Slide: " + currSlide);
		  
		currSlide = 72;		
	} else if (currSlide == 72){
		var rahmenbedingungenForm3 = document.getElementById("rahmenDerInfoweitergabe3");
		if ($('input[name=rahmenDerInfoweitergabe3]:checked').length == 0 && !checked) {
            promptNonresponse();
            checked = true;
		}else{
			//Collect Data
              if (rahmenbedingungenForm3[0].checked) {
               p4_q6_1 = nodes[0].q6_1 = "geschieht auf regelmäßiger Basis";
              } if (rahmenbedingungenForm3[1].checked) {
               p4_q6_2= nodes[0].q6_2 = "es gibt dafür vorgesehene Arbeitszeiten/Treffen (außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)";
              } if (rahmenbedingungenForm3[2].checked) {
               p4_q6_3 = nodes[0].q6_3 = "es werden bestimmte Methoden und Formate genutzt";
              } if (rahmenbedingungenForm3[3].checked) {
               p4_q6_4 = nodes[0].q6_4 = "geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks";
              }	if (rahmenbedingungenForm3[4].checked) {
               p4_q6_5 = nodes[0].q6_5 = "geschieht ausschließlich aus Eigeninitiative der beteiligten Personen";
              }	if (rahmenbedingungenForm3[5].checked) {
               p4_q6_6 = nodes[0].q6_6 = "wird von der Schulleitung gefördert";
              }	if (rahmenbedingungenForm3[6].checked) {
               p4_q6_7 = nodes[0].q6_7 = "wird von der Schulleitung angeordnet";
              } 
			  
			document.getElementById("rahmenbedingungenInfoweitergabe3").style.display = "none";
			document.getElementById("slide71").style.display = "block";
			checked = false;
			console.log("aktuelle Slide: " + currSlide);
			currSlide ++;
			showNext();
		}
}		
	  
	  
      //-------------------------------------------------------------------------
      // End of ShowNext(), Data Recording
      //-------------------------------------------------------------------------
		
	//TODO: diese currSlide entsprechend anpassen (letzte Slide)
	else if (currSlide == 73) {
		document.getElementById("rahmenbedingungenInfoweitergabe2").style.display = "none";
		
		var formalisierungForm = document.getElementById("gradDerFormalisierung");
		var formalisierungForm2 = document.getElementById("gradDerFormalisierung2");
		var formalisierungForm3 = document.getElementById("gradDerFormalisierung3");
		var rahmenbedingungenForm1 = document.getElementById("rahmenDerInfoweitergabe1");
		var rahmenbedingungenForm2 = document.getElementById("rahmenDerInfoweitergabe2");
		var rahmenbedingungenForm3 = document.getElementById("rahmenDerInfoweitergabe3");
		// If user has not selected an option, alert with popup
		 /* if (($('input[name=bnegpos]:checked').length == 0 || $('input[name=bvrivij]:checked').length == 0 || $('input[name=bwanver]:checked').length == 0) && !checked) {
            promptNonresponse();
            checked = true;
			
			} else {
            // Collect data before going on
			//was genau geschieht hier?
            nodes[0].q10_1 = $('input[name="bnegpos"]:checked').val();
            nodes[0].q10_2 = $('input[name="bvrivij"]:checked').val();
            nodes[0].q10_3 = $('input[name="bwanver"]:checked').val();
            //var bw = document.getElementById("bWarmth")
            */
		
            // Single array containing all answers
			//TODO: Array auf aktuelle Fragenzahl ergänzen!
            var answer = [document.getElementById("nomem").value,nodes[0].q1,nodes[0].q2,(nodes.length > 1) ? nodes[1].name : "", (nodes.length > 1) ? nodes[1].q4 : "",(nodes.length > 1) ? nodes[1].friendsWith : "",(nodes.length > 1) ? nodes[1].q6 : "",(nodes.length > 1) ? nodes[1].q7 : "",(nodes.length > 1) ? nodes[1].q8 : "",(nodes.length > 2) ? nodes[2].name : "",(nodes.length > 2) ? nodes[2].q4 : "",(nodes.length > 2) ? nodes[2].friendsWith : "",(nodes.length > 2) ? nodes[2].q6 : "",(nodes.length > 2) ? nodes[2].q7 : "",(nodes.length > 2) ? nodes[2].q8 : "",(nodes.length > 3) ? nodes[3].name : "",(nodes.length > 3) ? nodes[3].q4 : "",(nodes.length > 3) ? nodes[3].friendsWith : "",(nodes.length > 3) ? nodes[3].q6 : "",(nodes.length > 3) ? nodes[3].q7 : "",(nodes.length > 3) ? nodes[3].q8 : "",(nodes.length > 4) ? nodes[4].name : "",(nodes.length > 4) ? nodes[4].q4 : "",(nodes.length > 4) ? nodes[4].friendsWith : "",(nodes.length > 4) ? nodes[4].q6 : "",(nodes.length > 4) ? nodes[4].q7 : "",(nodes.length > 4) ? nodes[4].q8 : "",(nodes.length > 5) ? nodes[5].name : "",(nodes.length > 5) ? nodes[5].q4 : "",(nodes.length > 5) ? nodes[5].friendsWith : "",(nodes.length > 5) ? nodes[5].q6 : "",(nodes.length > 5) ? nodes[5].q7 : "",(nodes.length > 5) ? nodes[5].q8 : "",nodes[0].q9_1,nodes[0].q9_2,nodes[0].q9_3,nodes[0].q10_1,nodes[0].q10_2,nodes[0].q10_3];
                
            document.getElementById("qu1_id").value = answer.join(",");
            console.log("aktuelle Slide: " + currSlide);
			console.log(teilnehmercodeInput.value);
			
			//Post collected data to handler for recording
            $.post( "save_results.php", { 
            nomem: document.getElementById("nomem").value,
			teilnehmercode: teilnehmercodeInput.value,
			p1_name1: (nodes1.length > 1) ? p1_name[1] : "",
			p1_dd1_n1: (nodes1.length > 1) ? p1_dd1[1]: "",
			p1_dd2_n1: (nodes1.length > 1) ? p1_dd2[1] : "",
			p1_q1_n1_1: (nodes1.length > 1) ? nodes1[1].q1_1 : "",
			p1_q1_n1_2: (nodes1.length > 1) ? nodes1[1].q1_2 : "",
			p1_q1_n1_3: (nodes1.length > 1) ? nodes1[1].q1_3 : "",
			p1_q1_n1_4: (nodes1.length > 1) ? nodes1[1].q1_4 : "",
			p1_q1_n1_5: (nodes1.length > 1) ? nodes1[1].q1_5 : "",
			p1_q1_n1_6: (nodes1.length > 1) ? nodes1[1].q1_6 : "",
			p1_q1_n1_7: (nodes1.length > 1) ? nodes1[1].q1_7: "",
			p1_q2_n1: (nodes1.length > 1) ? nodes1[1].q2 : "",
			p1_q3_n1: (nodes1.length > 1) ? nodes1[1].q3 : "",
			p1_q4_n1_1: (nodes1.length > 1) ? nodes1[1].q4_1 : "",
			p1_q4_n1_2: (nodes1.length > 1) ? nodes1[1].q4_2: "",
			p1_q4_n1_3: (nodes1.length > 1) ? nodes1[1].q4_3 : "",
			p1_q4_n1_4: (nodes1.length > 1) ? nodes1[1].q4_4 : "",
			p1_q4_n1_5: (nodes1.length > 1) ? nodes1[1].q4_5 : "",
			p1_q4_n1_6: (nodes1.length > 1) ? nodes1[1].q4_6: "",
			p1_q4_n1_7: (nodes1.length > 1) ? nodes1[1].q4_7 : "",
			p1_q4_n1_8: (nodes1.length > 1) ? nodes1[1].q4_8 : "",
			p1_q4_n1_9: (nodes1.length > 1) ? nodes1[1].q4_9: "",
			p1_q4_n1_10: (nodes1.length > 1) ? nodes1[1].q4_10 : "",
			p1_q4_n1_11: (nodes1.length > 1) ? nodes1[1].q4_11 : "",
			p1_q4_n1_12: (nodes1.length > 1) ? nodes1[1].q4_12 : "",
			p1_q4_n1_13: (nodes1.length > 1) ? nodes1[1].q4_13 : "",
			p1_q5_n1: (nodes1.length > 1) ? nodes1[1].q5 : "",
			p1_link_n1: (nodes1.length > 1) ? p1_link[1] : "",

			p1_name2: (nodes1.length > 2) ? p1_name[2] : "",
			p1_dd1_n2: (nodes1.length > 2) ? p1_dd1[2]: "",
			p1_dd2_n2: (nodes1.length > 2) ? p1_dd2[2] : "",
			p1_q1_n2_1: (nodes1.length > 2) ? nodes1[2].q1_1 : "",
			p1_q1_n2_2: (nodes1.length > 2) ? nodes1[2].q1_2 : "",
			p1_q1_n2_3: (nodes1.length > 2) ? nodes1[2].q1_3 : "",
			p1_q1_n2_4: (nodes1.length > 2) ? nodes1[2].q1_4 : "",
			p1_q1_n2_5: (nodes1.length > 2) ? nodes1[2].q1_5 : "",
			p1_q1_n2_6: (nodes1.length > 2) ? nodes1[2].q1_6 : "",
			p1_q1_n2_7: (nodes1.length > 2) ? nodes1[2].q1_7: "",
			p1_q2_n2: (nodes1.length > 2) ? nodes1[2].q2 : "",
			p1_q3_n2: (nodes1.length > 2) ? nodes1[2].q3 : "",
			p1_q4_n2_1: (nodes1.length > 2) ? nodes1[2].q4_1 : "",
			p1_q4_n2_2: (nodes1.length > 2) ? nodes1[2].q4_2: "",
			p1_q4_n2_3: (nodes1.length > 2) ? nodes1[2].q4_3 : "",
			p1_q4_n2_4: (nodes1.length > 2) ? nodes1[2].q4_4 : "",
			p1_q4_n2_5: (nodes1.length > 2) ? nodes1[2].q4_5 : "",
			p1_q4_n2_6: (nodes1.length > 2) ? nodes1[2].q4_6: "",
			p1_q4_n2_7: (nodes1.length > 2) ? nodes1[2].q4_7 : "",
			p1_q4_n2_8: (nodes1.length > 2) ? nodes1[2].q4_8 : "",
			p1_q4_n2_9: (nodes1.length > 2) ? nodes1[2].q4_9: "",
			p1_q4_n2_10: (nodes1.length > 2) ? nodes1[2].q4_10 : "",
			p1_q4_n2_11: (nodes1.length > 2) ? nodes1[2].q4_11 : "",
			p1_q4_n2_12: (nodes1.length > 2) ? nodes1[2].q4_12 : "",
			p1_q4_n2_13: (nodes1.length > 2) ? nodes1[2].q4_13 : "",
			p1_q5_n2: (nodes1.length > 2) ? nodes1[2].q5 : "",
			p1_link_n2: (nodes1.length > 2) ? p1_link[2] : "",

			p1_name3: (nodes1.length > 3) ? p1_name[3] : "",
			p1_dd1_n3: (nodes1.length > 3) ? p1_dd1[3]: "",
			p1_dd2_n3: (nodes1.length > 3) ? p1_dd2[3] : "",
			p1_q1_n3_1: (nodes1.length > 3) ? nodes1[3].q1_1 : "",
			p1_q1_n3_2: (nodes1.length > 3) ? nodes1[3].q1_2 : "",
			p1_q1_n3_3: (nodes1.length > 3) ? nodes1[3].q1_3 : "",
			p1_q1_n3_4: (nodes1.length > 3) ? nodes1[3].q1_4 : "",
			p1_q1_n3_5: (nodes1.length > 3) ? nodes1[3].q1_5 : "",
			p1_q1_n3_6: (nodes1.length > 3) ? nodes1[3].q1_6 : "",
			p1_q1_n3_7: (nodes1.length > 3) ? nodes1[3].q1_7: "",
			p1_q2_n3: (nodes1.length > 3) ? nodes1[3].q2 : "",
			p1_q3_n3: (nodes1.length > 3) ? nodes1[3].q3 : "",
			p1_q4_n3_1: (nodes1.length > 3) ? nodes1[3].q4_1 : "",
			p1_q4_n3_2: (nodes1.length > 3) ? nodes1[3].q4_2: "",
			p1_q4_n3_3: (nodes1.length > 3) ? nodes1[3].q4_3 : "",
			p1_q4_n3_4: (nodes1.length > 3) ? nodes1[3].q4_4 : "",
			p1_q4_n3_5: (nodes1.length > 3) ? nodes1[3].q4_5 : "",
			p1_q4_n3_6: (nodes1.length > 3) ? nodes1[3].q4_6: "",
			p1_q4_n3_7: (nodes1.length > 3) ? nodes1[3].q4_7 : "",
			p1_q4_n3_8: (nodes1.length > 3) ? nodes1[3].q4_8 : "",
			p1_q4_n3_9: (nodes1.length > 3) ? nodes1[3].q4_9: "",
			p1_q4_n3_10: (nodes1.length > 3) ? nodes1[3].q4_10 : "",
			p1_q4_n3_11: (nodes1.length > 3) ? nodes1[3].q4_11 : "",
			p1_q4_n3_12: (nodes1.length > 3) ? nodes1[3].q4_12 : "",
			p1_q4_n3_13: (nodes1.length > 3) ? nodes1[3].q4_13 : "",
			p1_q5_n3: (nodes1.length > 3) ? nodes1[3].q5 : "",
			p1_link_n3: (nodes1.length > 3) ? p1_link[3] : "",

			p1_name4: (nodes1.length > 4) ? p1_name[4] : "",
			p1_dd1_n4: (nodes1.length > 4) ? p1_dd1[4]: "",
			p1_dd2_n4: (nodes1.length > 4) ? p1_dd2[4] : "",
			p1_q1_n4_1: (nodes1.length > 4) ? nodes1[4].q1_1 : "",
			p1_q1_n4_2: (nodes1.length > 4) ? nodes1[4].q1_2 : "",
			p1_q1_n4_3: (nodes1.length > 4) ? nodes1[4].q1_3 : "",
			p1_q1_n4_4: (nodes1.length > 4) ? nodes1[4].q1_4 : "",
			p1_q1_n4_5: (nodes1.length > 4) ? nodes1[4].q1_5 : "",
			p1_q1_n4_6: (nodes1.length > 4) ? nodes1[4].q1_6 : "",
			p1_q1_n4_7: (nodes1.length > 4) ? nodes1[4].q1_7: "",
			p1_q2_n4: (nodes1.length > 4) ? nodes1[4].q2 : "",
			p1_q3_n4: (nodes1.length > 4) ? nodes1[4].q3 : "",
			p1_q4_n4_1: (nodes1.length > 4) ? nodes1[4].q4_1 : "",
			p1_q4_n4_2: (nodes1.length > 4) ? nodes1[4].q4_2: "",
			p1_q4_n4_3: (nodes1.length > 4) ? nodes1[4].q4_3 : "",
			p1_q4_n4_4: (nodes1.length > 4) ? nodes1[4].q4_4 : "",
			p1_q4_n4_5: (nodes1.length > 4) ? nodes1[4].q4_5 : "",
			p1_q4_n4_6: (nodes1.length > 4) ? nodes1[4].q4_6: "",
			p1_q4_n4_7: (nodes1.length > 4) ? nodes1[4].q4_7 : "",
			p1_q4_n4_8: (nodes1.length > 4) ? nodes1[4].q4_8 : "",
			p1_q4_n4_9: (nodes1.length > 4) ? nodes1[4].q4_9: "",
			p1_q4_n4_10: (nodes1.length > 4) ? nodes1[4].q4_10 : "",
			p1_q4_n4_11: (nodes1.length > 4) ? nodes1[4].q4_11 : "",
			p1_q4_n4_12: (nodes1.length > 4) ? nodes1[4].q4_12 : "",
			p1_q4_n4_13: (nodes1.length > 4) ? nodes1[4].q4_13 : "",
			p1_q5_n4: (nodes1.length > 4) ? nodes1[4].q5 : "",
			p1_link_n4: (nodes1.length > 4) ? p1_link[4] : "",

			p1_name5: (nodes1.length > 5) ? p1_name[5] : "",
			p1_dd1_n5: (nodes1.length > 5) ? p1_dd1[5]: "",
			p1_dd2_n5: (nodes1.length > 5) ? p1_dd2[5] : "",
			p1_q1_n5_1: (nodes1.length > 5) ? nodes1[5].q1_1 : "",
			p1_q1_n5_2: (nodes1.length > 5) ? nodes1[5].q1_2 : "",
			p1_q1_n5_3: (nodes1.length > 5) ? nodes1[5].q1_3 : "",
			p1_q1_n5_4: (nodes1.length > 5) ? nodes1[5].q1_4 : "",
			p1_q1_n5_5: (nodes1.length > 5) ? nodes1[5].q1_5 : "",
			p1_q1_n5_6: (nodes1.length > 5) ? nodes1[5].q1_6 : "",
			p1_q1_n5_7: (nodes1.length > 5) ? nodes1[5].q1_7: "",
			p1_q2_n5: (nodes1.length > 5) ? nodes1[5].q2 : "",
			p1_q3_n5: (nodes1.length > 5) ? nodes1[5].q3 : "",
			p1_q4_n5_1: (nodes1.length > 5) ? nodes1[5].q4_1 : "",
			p1_q4_n5_2: (nodes1.length > 5) ? nodes1[5].q4_2: "",
			p1_q4_n5_3: (nodes1.length > 5) ? nodes1[5].q4_3 : "",
			p1_q4_n5_4: (nodes1.length > 5) ? nodes1[5].q4_4 : "",
			p1_q4_n5_5: (nodes1.length > 5) ? nodes1[5].q4_5 : "",
			p1_q4_n5_6: (nodes1.length > 5) ? nodes1[5].q4_6: "",
			p1_q4_n5_7: (nodes1.length > 5) ? nodes1[5].q4_7 : "",
			p1_q4_n5_8: (nodes1.length > 5) ? nodes1[5].q4_8 : "",
			p1_q4_n5_9: (nodes1.length > 5) ? nodes1[5].q4_9: "",
			p1_q4_n5_10: (nodes1.length > 5) ? nodes1[5].q4_10 : "",
			p1_q4_n5_11: (nodes1.length > 5) ? nodes1[5].q4_11 : "",
			p1_q4_n5_12: (nodes1.length > 5) ? nodes1[5].q4_12 : "",
			p1_q4_n5_13: (nodes1.length > 5) ? nodes1[5].q4_13 : "",
			p1_q5_n5: (nodes1.length > 5) ? nodes1[5].q5 : "",
			p1_link_n5: (nodes1.length > 5) ? p1_link[5] : "",

			/*---Part2---*/

			p2_name1: (nodes2.length > 1) ? p2_name[1] : "",
			p2_dd1_n1: (nodes2.length > 1) ? p2_dd1[1]: "",
			p2_dd2_n1: (nodes2.length > 1) ? p2_dd2[1] : "",
			p2_q1_n1_1: (nodes2.length > 1) ? nodes2[1].q1_1 : "",
			p2_q1_n1_2: (nodes2.length > 1) ? nodes2[1].q1_2 : "",
			p2_q1_n1_3: (nodes2.length > 1) ? nodes2[1].q1_3 : "",
			p2_q1_n1_4: (nodes2.length > 1) ? nodes2[1].q1_4 : "",
			p2_q1_n1_5: (nodes2.length > 1) ? nodes2[1].q1_5 : "",
			p2_q1_n1_6: (nodes2.length > 1) ? nodes2[1].q1_6 : "",
			p2_q1_n1_7: (nodes2.length > 1) ? nodes2[1].q1_7: "",
			p2_q2_n1: (nodes2.length > 1) ? nodes2[1].q2 : "",
			p2_q3_n1: (nodes2.length > 1) ? nodes2[1].q3 : "",
			p2_q4_n1_1: (nodes2.length > 1) ? nodes2[1].q4_1 : "",
			p2_q4_n1_2: (nodes2.length > 1) ? nodes2[1].q4_2: "",
			p2_q4_n1_3: (nodes2.length > 1) ? nodes2[1].q4_3 : "",
			p2_q4_n1_4: (nodes2.length > 1) ? nodes2[1].q4_4 : "",
			p2_q4_n1_5: (nodes2.length > 1) ? nodes2[1].q4_5 : "",
			p2_q4_n1_6: (nodes2.length > 1) ? nodes2[1].q4_6: "",
			p2_q4_n1_7: (nodes2.length > 1) ? nodes2[1].q4_7 : "",
			p2_q4_n1_8: (nodes2.length > 1) ? nodes2[1].q4_8 : "",
			p2_q4_n1_9: (nodes2.length > 1) ? nodes2[1].q4_9: "",
			p2_q4_n1_10: (nodes2.length > 1) ? nodes2[1].q4_10 : "",
			p2_q4_n1_11: (nodes2.length > 1) ? nodes2[1].q4_11 : "",
			p2_q4_n1_12: (nodes2.length > 1) ? nodes2[1].q4_12 : "",
			p2_q4_n1_13: (nodes2.length > 1) ? nodes2[1].q4_13 : "",
			p2_q5_n1: (nodes2.length > 1) ? nodes2[1].q5 : "",
			p2_link_n1: (nodes2.length > 1) ? p2_link[1] : "",

			p2_name2: (nodes2.length > 2) ? p2_name[2] : "",
			p2_dd1_n2: (nodes2.length > 2) ? p2_dd1[2]: "",
			p2_dd2_n2: (nodes2.length > 2) ? p2_dd2[2] : "",
			p2_q1_n2_1: (nodes2.length > 2) ? nodes2[2].q1_1 : "",
			p2_q1_n2_2: (nodes2.length > 2) ? nodes2[2].q1_2 : "",
			p2_q1_n2_3: (nodes2.length > 2) ? nodes2[2].q1_3 : "",
			p2_q1_n2_4: (nodes2.length > 2) ? nodes2[2].q1_4 : "",
			p2_q1_n2_5: (nodes2.length > 2) ? nodes2[2].q1_5 : "",
			p2_q1_n2_6: (nodes2.length > 2) ? nodes2[2].q1_6 : "",
			p2_q1_n2_7: (nodes2.length > 2) ? nodes2[2].q1_7: "",
			p2_q2_n2: (nodes2.length > 2) ? nodes2[2].q2 : "",
			p2_q3_n2: (nodes2.length > 2) ? nodes2[2].q3 : "",
			p2_q4_n2_1: (nodes2.length > 2) ? nodes2[2].q4_1 : "",
			p2_q4_n2_2: (nodes2.length > 2) ? nodes2[2].q4_2: "",
			p2_q4_n2_3: (nodes2.length > 2) ? nodes2[2].q4_3 : "",
			p2_q4_n2_4: (nodes2.length > 2) ? nodes2[2].q4_4 : "",
			p2_q4_n2_5: (nodes2.length > 2) ? nodes2[2].q4_5 : "",
			p2_q4_n2_6: (nodes2.length > 2) ? nodes2[2].q4_6: "",
			p2_q4_n2_7: (nodes2.length > 2) ? nodes2[2].q4_7 : "",
			p2_q4_n2_8: (nodes2.length > 2) ? nodes2[2].q4_8 : "",
			p2_q4_n2_9: (nodes2.length > 2) ? nodes2[2].q4_9: "",
			p2_q4_n2_10: (nodes2.length > 2) ? nodes2[2].q4_10 : "",
			p2_q4_n2_11: (nodes2.length > 2) ? nodes2[2].q4_11 : "",
			p2_q4_n2_12: (nodes2.length > 2) ? nodes2[2].q4_12 : "",
			p2_q4_n2_13: (nodes2.length > 2) ? nodes2[2].q4_13 : "",
			p2_q5_n2: (nodes2.length > 2) ? nodes2[2].q5 : "",
			p2_link_n2: (nodes2.length > 2) ? p2_link[2] : "",

			p2_name3: (nodes2.length > 3) ? p2_name[3] : "",
			p2_dd1_n3: (nodes2.length > 3) ? p2_dd1[3]: "",
			p2_dd2_n3: (nodes2.length > 3) ? p2_dd2[3] : "",
			p2_q1_n3_1: (nodes2.length > 3) ? nodes2[3].q1_1 : "",
			p2_q1_n3_2: (nodes2.length > 3) ? nodes2[3].q1_2 : "",
			p2_q1_n3_3: (nodes2.length > 3) ? nodes2[3].q1_3 : "",
			p2_q1_n3_4: (nodes2.length > 3) ? nodes2[3].q1_4 : "",
			p2_q1_n3_5: (nodes2.length > 3) ? nodes2[3].q1_5 : "",
			p2_q1_n3_6: (nodes2.length > 3) ? nodes2[3].q1_6 : "",
			p2_q1_n3_7: (nodes2.length > 3) ? nodes2[3].q1_7: "",
			p2_q2_n3: (nodes2.length > 3) ? nodes2[3].q2 : "",
			p2_q3_n3: (nodes2.length > 3) ? nodes2[3].q3 : "",
			p2_q4_n3_1: (nodes2.length > 3) ? nodes2[3].q4_1 : "",
			p2_q4_n3_2: (nodes2.length > 3) ? nodes2[3].q4_2: "",
			p2_q4_n3_3: (nodes2.length > 3) ? nodes2[3].q4_3 : "",
			p2_q4_n3_4: (nodes2.length > 3) ? nodes2[3].q4_4 : "",
			p2_q4_n3_5: (nodes2.length > 3) ? nodes2[3].q4_5 : "",
			p2_q4_n3_6: (nodes2.length > 3) ? nodes2[3].q4_6: "",
			p2_q4_n3_7: (nodes2.length > 3) ? nodes2[3].q4_7 : "",
			p2_q4_n3_8: (nodes2.length > 3) ? nodes2[3].q4_8 : "",
			p2_q4_n3_9: (nodes2.length > 3) ? nodes2[3].q4_9: "",
			p2_q4_n3_10: (nodes2.length > 3) ? nodes2[3].q4_10 : "",
			p2_q4_n3_11: (nodes2.length > 3) ? nodes2[3].q4_11 : "",
			p2_q4_n3_12: (nodes2.length > 3) ? nodes2[3].q4_12 : "",
			p2_q4_n3_13: (nodes2.length > 3) ? nodes2[3].q4_13 : "",
			p2_q5_n3: (nodes2.length > 3) ? nodes2[3].q5 : "",
			p2_link_n3: (nodes2.length > 3) ? p2_link[3] : "",

			p2_name4: (nodes2.length > 4) ? p2_name[4] : "",
			p2_dd1_n4: (nodes2.length > 4) ? p2_dd1[4]: "",
			p2_dd2_n4: (nodes2.length > 4) ? p2_dd2[4] : "",
			p2_q1_n4_1: (nodes2.length > 4) ? nodes2[4].q1_1 : "",
			p2_q1_n4_2: (nodes2.length > 4) ? nodes2[4].q1_2 : "",
			p2_q1_n4_3: (nodes2.length > 4) ? nodes2[4].q1_3 : "",
			p2_q1_n4_4: (nodes2.length > 4) ? nodes2[4].q1_4 : "",
			p2_q1_n4_5: (nodes2.length > 4) ? nodes2[4].q1_5 : "",
			p2_q1_n4_6: (nodes2.length > 4) ? nodes2[4].q1_6 : "",
			p2_q1_n4_7: (nodes2.length > 4) ? nodes2[4].q1_7: "",
			p2_q2_n4: (nodes2.length > 4) ? nodes2[4].q2 : "",
			p2_q3_n4: (nodes2.length > 4) ? nodes2[4].q3 : "",
			p2_q4_n4_1: (nodes2.length > 4) ? nodes2[4].q4_1 : "",
			p2_q4_n4_2: (nodes2.length > 4) ? nodes2[4].q4_2: "",
			p2_q4_n4_3: (nodes2.length > 4) ? nodes2[4].q4_3 : "",
			p2_q4_n4_4: (nodes2.length > 4) ? nodes2[4].q4_4 : "",
			p2_q4_n4_5: (nodes2.length > 4) ? nodes2[4].q4_5 : "",
			p2_q4_n4_6: (nodes2.length > 4) ? nodes2[4].q4_6: "",
			p2_q4_n4_7: (nodes2.length > 4) ? nodes2[4].q4_7 : "",
			p2_q4_n4_8: (nodes2.length > 4) ? nodes2[4].q4_8 : "",
			p2_q4_n4_9: (nodes2.length > 4) ? nodes2[4].q4_9: "",
			p2_q4_n4_10: (nodes2.length > 4) ? nodes2[4].q4_10 : "",
			p2_q4_n4_11: (nodes2.length > 4) ? nodes2[4].q4_11 : "",
			p2_q4_n4_12: (nodes2.length > 4) ? nodes2[4].q4_12 : "",
			p2_q4_n4_13: (nodes2.length > 4) ? nodes2[4].q4_13 : "",
			p2_q5_n4: (nodes2.length > 4) ? nodes2[4].q5 : "",
			p2_link_n4: (nodes2.length > 4) ? p2_link[4] : "",

			p2_name5: (nodes2.length > 5) ? p2_name[5] : "",
			p2_dd1_n5: (nodes2.length > 5) ? p2_dd1[5]: "",
			p2_dd2_n5: (nodes2.length > 5) ? p2_dd2[5] : "",
			p2_q1_n5_1: (nodes2.length > 5) ? nodes2[5].q1_1 : "",
			p2_q1_n5_2: (nodes2.length > 5) ? nodes2[5].q1_2 : "",
			p2_q1_n5_3: (nodes2.length > 5) ? nodes2[5].q1_3 : "",
			p2_q1_n5_4: (nodes2.length > 5) ? nodes2[5].q1_4 : "",
			p2_q1_n5_5: (nodes2.length > 5) ? nodes2[5].q1_5 : "",
			p2_q1_n5_6: (nodes2.length > 5) ? nodes2[5].q1_6 : "",
			p2_q1_n5_7: (nodes2.length > 5) ? nodes2[5].q1_7: "",
			p2_q2_n5: (nodes2.length > 5) ? nodes2[5].q2 : "",
			p2_q3_n5: (nodes2.length > 5) ? nodes2[5].q3 : "",
			p2_q4_n5_1: (nodes2.length > 5) ? nodes2[5].q4_1 : "",
			p2_q4_n5_2: (nodes2.length > 5) ? nodes2[5].q4_2: "",
			p2_q4_n5_3: (nodes2.length > 5) ? nodes2[5].q4_3 : "",
			p2_q4_n5_4: (nodes2.length > 5) ? nodes2[5].q4_4 : "",
			p2_q4_n5_5: (nodes2.length > 5) ? nodes2[5].q4_5 : "",
			p2_q4_n5_6: (nodes2.length > 5) ? nodes2[5].q4_6: "",
			p2_q4_n5_7: (nodes2.length > 5) ? nodes2[5].q4_7 : "",
			p2_q4_n5_8: (nodes2.length > 5) ? nodes2[5].q4_8 : "",
			p2_q4_n5_9: (nodes2.length > 5) ? nodes2[5].q4_9: "",
			p2_q4_n5_10: (nodes2.length > 5) ? nodes2[5].q4_10 : "",
			p2_q4_n5_11: (nodes2.length > 5) ? nodes2[5].q4_11 : "",
			p2_q4_n5_12: (nodes2.length > 5) ? nodes2[5].q4_12 : "",
			p2_q4_n5_13: (nodes2.length > 5) ? nodes2[5].q4_13 : "",
			p2_q5_n5: (nodes2.length > 5) ? nodes2[5].q5 : "",
			p2_link_n5: (nodes2.length > 5) ? p2_link[5] : "",

			p2_q6_1: (formalisierungForm[0].checked) ? p2_q6_1 : "",
			p2_q6_2: (formalisierungForm[1].checked) ? p2_q6_2 : "",
			p2_q6_3: (formalisierungForm[2].checked) ? p2_q6_3 : "",
			p2_q6_4: (formalisierungForm[3].checked) ? p2_q6_4 : "",
			p2_q6_5: (formalisierungForm[4].checked) ? p2_q6_5 : "",
			p2_q6_6: (formalisierungForm[5].checked) ? p2_q6_6 : "",
			p2_q6_7: (formalisierungForm[6].checked) ? p2_q6_7 : "",
			p2_q6_8: (formalisierungForm[7].checked) ? p2_q6_8 : "",
			p2_q6_9: (formalisierungForm[8].checked) ? p2_q6_9 : "",

			p2_q7_1: (rahmenbedingungenForm1[0].checked) ? p2_q7_1 : "",
			p2_q7_2: (rahmenbedingungenForm1[1].checked) ? p2_q7_2 : "",
			p2_q7_3: (rahmenbedingungenForm1[2].checked) ? p2_q7_3 : "", 
			p2_q7_4: (rahmenbedingungenForm1[3].checked) ? p2_q7_4 : "",
			p2_q7_5: (rahmenbedingungenForm1[4].checked) ? p2_q7_5 : "",
			p2_q7_6: (rahmenbedingungenForm1[5].checked) ? p2_q7_6 : "",
			p2_q7_7: (rahmenbedingungenForm1[6].checked) ? p2_q7_7 : "",
			p2_q7_8: (rahmenbedingungenForm1[7].checked) ? p2_q7_8 : "",
			p2_q7_9: (rahmenbedingungenForm1[8].checked) ? p2_q7_9 : "",

			/*---Part3---*/

			p3_name1: (nodes3.length > 1) ? p3_name[1] : "",
			p3_dd1_n1: (nodes3.length > 1) ? p3_dd1[1]: "",
			p3_dd2_n1: (nodes3.length > 1) ? p3_dd2[1] : "",
			p3_q1_n1_1: (nodes3.length > 1) ? nodes3[1].q1_1 : "",
			p3_q1_n1_2: (nodes3.length > 1) ? nodes3[1].q1_2 : "",
			p3_q1_n1_3: (nodes3.length > 1) ? nodes3[1].q1_3 : "",
			p3_q1_n1_4: (nodes3.length > 1) ? nodes3[1].q1_4 : "",
			p3_q1_n1_5: (nodes3.length > 1) ? nodes3[1].q1_5 : "",
			p3_q1_n1_6: (nodes3.length > 1) ? nodes3[1].q1_6 : "",
			p3_q1_n1_7: (nodes3.length > 1) ? nodes3[1].q1_7: "",
			p3_q2_n1: (nodes3.length > 1) ? nodes3[1].q2 : "",
			p3_q3_n1: (nodes3.length > 1) ? nodes3[1].q3 : "",
			p3_q4_n1_1: (nodes3.length > 1) ? nodes3[1].q4_1 : "",
			p3_q4_n1_2: (nodes3.length > 1) ? nodes3[1].q4_2: "",
			p3_q4_n1_3: (nodes3.length > 1) ? nodes3[1].q4_3 : "",
			p3_q4_n1_4: (nodes3.length > 1) ? nodes3[1].q4_4 : "",
			p3_q4_n1_5: (nodes3.length > 1) ? nodes3[1].q4_5 : "",
			p3_q4_n1_6: (nodes3.length > 1) ? nodes3[1].q4_6: "",
			p3_q4_n1_7: (nodes3.length > 1) ? nodes3[1].q4_7 : "",
			p3_q4_n1_8: (nodes3.length > 1) ? nodes3[1].q4_8 : "",
			p3_q4_n1_9: (nodes3.length > 1) ? nodes3[1].q4_9: "",
			p3_q4_n1_10: (nodes3.length > 1) ? nodes3[1].q4_10 : "",
			p3_q4_n1_11: (nodes3.length > 1) ? nodes3[1].q4_11 : "",
			p3_q4_n1_12: (nodes3.length > 1) ? nodes3[1].q4_12 : "",
			p3_q4_n1_13: (nodes3.length > 1) ? nodes3[1].q4_13 : "",
			p3_q5_n1: (nodes3.length > 1) ? nodes3[1].q5 : "",
			p3_link_n1: (nodes3.length > 1) ? p3_link[1] : "",
			
			p3_name2: (nodes3.length > 2) ? p3_name[2] : "",
			p3_dd1_n2: (nodes3.length > 2) ? p3_dd1[2]: "",
			p3_dd2_n2: (nodes3.length > 2) ? p3_dd2[2] : "",
			p3_q1_n2_1: (nodes3.length > 2) ? nodes3[2].q1_1 : "",
			p3_q1_n2_2: (nodes3.length > 2) ? nodes3[2].q1_2 : "",
			p3_q1_n2_3: (nodes3.length > 2) ? nodes3[2].q1_3 : "",
			p3_q1_n2_4: (nodes3.length > 2) ? nodes3[2].q1_4 : "",
			p3_q1_n2_5: (nodes3.length > 2) ? nodes3[2].q1_5 : "",
			p3_q1_n2_6: (nodes3.length > 2) ? nodes3[2].q1_6 : "",
			p3_q1_n2_7: (nodes3.length > 2) ? nodes3[2].q1_7: "",
			p3_q2_n2: (nodes3.length > 2) ? nodes3[2].q2 : "",
			p3_q3_n2: (nodes3.length > 2) ? nodes3[2].q3 : "",
			p3_q4_n2_1: (nodes3.length > 2) ? nodes3[2].q4_1 : "",
			p3_q4_n2_2: (nodes3.length > 2) ? nodes3[2].q4_2: "",
			p3_q4_n2_3: (nodes3.length > 2) ? nodes3[2].q4_3 : "",
			p3_q4_n2_4: (nodes3.length > 2) ? nodes3[2].q4_4 : "",
			p3_q4_n2_5: (nodes3.length > 2) ? nodes3[2].q4_5 : "",
			p3_q4_n2_6: (nodes3.length > 2) ? nodes3[2].q4_6: "",
			p3_q4_n2_7: (nodes3.length > 2) ? nodes3[2].q4_7 : "",
			p3_q4_n2_8: (nodes3.length > 2) ? nodes3[2].q4_8 : "",
			p3_q4_n2_9: (nodes3.length > 2) ? nodes3[2].q4_9: "",
			p3_q4_n2_10: (nodes3.length > 2) ? nodes3[2].q4_10 : "",
			p3_q4_n2_11: (nodes3.length > 2) ? nodes3[2].q4_11 : "",
			p3_q4_n2_12: (nodes3.length > 2) ? nodes3[2].q4_12 : "",
			p3_q4_n2_13: (nodes3.length > 2) ? nodes3[2].q4_13 : "",
			p3_q5_n2: (nodes3.length > 2) ? nodes3[2].q5 : "",
			p3_link_n2: (nodes3.length > 2) ? p3_link[2] : "",
			
			p3_name3: (nodes3.length > 3) ? p3_name[3] : "",
			p3_dd1_n3: (nodes3.length > 3) ? p3_dd1[3]: "",
			p3_dd2_n3: (nodes3.length > 3) ? p3_dd2[3] : "",
			p3_q1_n3_1: (nodes3.length > 3) ? nodes3[3].q1_1 : "",
			p3_q1_n3_2: (nodes3.length > 3) ? nodes3[3].q1_2 : "",
			p3_q1_n3_3: (nodes3.length > 3) ? nodes3[3].q1_3 : "",
			p3_q1_n3_4: (nodes3.length > 3) ? nodes3[3].q1_4 : "",
			p3_q1_n3_5: (nodes3.length > 3) ? nodes3[3].q1_5 : "",
			p3_q1_n3_6: (nodes3.length > 3) ? nodes3[3].q1_6 : "",
			p3_q1_n3_7: (nodes3.length > 3) ? nodes3[3].q1_7: "",
			p3_q2_n3: (nodes3.length > 3) ? nodes3[3].q2 : "",
			p3_q3_n3: (nodes3.length > 3) ? nodes3[3].q3 : "",
			p3_q4_n3_1: (nodes3.length > 3) ? nodes3[3].q4_1 : "",
			p3_q4_n3_2: (nodes3.length > 3) ? nodes3[3].q4_2: "",
			p3_q4_n3_3: (nodes3.length > 3) ? nodes3[3].q4_3 : "",
			p3_q4_n3_4: (nodes3.length > 3) ? nodes3[3].q4_4 : "",
			p3_q4_n3_5: (nodes3.length > 3) ? nodes3[3].q4_5 : "",
			p3_q4_n3_6: (nodes3.length > 3) ? nodes3[3].q4_6: "",
			p3_q4_n3_7: (nodes3.length > 3) ? nodes3[3].q4_7 : "",
			p3_q4_n3_8: (nodes3.length > 3) ? nodes3[3].q4_8 : "",
			p3_q4_n3_9: (nodes3.length > 3) ? nodes3[3].q4_9: "",
			p3_q4_n3_10: (nodes3.length > 3) ? nodes3[3].q4_10 : "",
			p3_q4_n3_11: (nodes3.length > 3) ? nodes3[3].q4_11 : "",
			p3_q4_n3_12: (nodes3.length > 3) ? nodes3[3].q4_12 : "",
			p3_q4_n3_13: (nodes3.length > 3) ? nodes3[3].q4_13 : "",
			p3_q5_n3: (nodes3.length > 3) ? nodes3[3].q5 : "",
			p3_link_n3: (nodes3.length > 3) ? p3_link[3] : "",

			p3_name4: (nodes3.length > 4) ? p3_name[4] : "",
			p3_dd1_n4: (nodes3.length > 4) ? p3_dd1[4]: "",
			p3_dd2_n4: (nodes3.length > 4) ? p3_dd2[4] : "",
			p3_q1_n4_1: (nodes3.length > 4) ? nodes3[4].q1_1 : "",
			p3_q1_n4_2: (nodes3.length > 4) ? nodes3[4].q1_2 : "",
			p3_q1_n4_3: (nodes3.length > 4) ? nodes3[4].q1_3 : "",
			p3_q1_n4_4: (nodes3.length > 4) ? nodes3[4].q1_4 : "",
			p3_q1_n4_5: (nodes3.length > 4) ? nodes3[4].q1_5 : "",
			p3_q1_n4_6: (nodes3.length > 4) ? nodes3[4].q1_6 : "",
			p3_q1_n4_7: (nodes3.length > 4) ? nodes3[4].q1_7: "",
			p3_q2_n4: (nodes3.length > 4) ? nodes3[4].q2 : "",
			p3_q3_n4: (nodes3.length > 4) ? nodes3[4].q3 : "",
			p3_q4_n4_1: (nodes3.length > 4) ? nodes3[4].q4_1 : "",
			p3_q4_n4_2: (nodes3.length > 4) ? nodes3[4].q4_2: "",
			p3_q4_n4_3: (nodes3.length > 4) ? nodes3[4].q4_3 : "",
			p3_q4_n4_4: (nodes3.length > 4) ? nodes3[4].q4_4 : "",
			p3_q4_n4_5: (nodes3.length > 4) ? nodes3[4].q4_5 : "",
			p3_q4_n4_6: (nodes3.length > 4) ? nodes3[4].q4_6: "",
			p3_q4_n4_7: (nodes3.length > 4) ? nodes3[4].q4_7 : "",
			p3_q4_n4_8: (nodes3.length > 4) ? nodes3[4].q4_8 : "",
			p3_q4_n4_9: (nodes3.length > 4) ? nodes3[4].q4_9: "",
			p3_q4_n4_10: (nodes3.length > 4) ? nodes3[4].q4_10 : "",
			p3_q4_n4_11: (nodes3.length > 4) ? nodes3[4].q4_11 : "",
			p3_q4_n4_12: (nodes3.length > 4) ? nodes3[4].q4_12 : "",
			p3_q4_n4_13: (nodes3.length > 4) ? nodes3[4].q4_13 : "",
			p3_q5_n4: (nodes3.length > 4) ? nodes3[4].q5 : "",
			p3_link_n4: (nodes3.length > 4) ? p3_link[4] : "",

			p3_name5: (nodes3.length > 5) ? p3_name[5] : "",
			p3_dd1_n5: (nodes3.length > 5) ? p3_dd1[5]: "",
			p3_dd2_n5: (nodes3.length > 5) ? p3_dd2[5] : "",
			p3_q1_n5_1: (nodes3.length > 5) ? nodes3[5].q1_1 : "",
			p3_q1_n5_2: (nodes3.length > 5) ? nodes3[5].q1_2 : "",
			p3_q1_n5_3: (nodes3.length > 5) ? nodes3[5].q1_3 : "",
			p3_q1_n5_4: (nodes3.length > 5) ? nodes3[5].q1_4 : "",
			p3_q1_n5_5: (nodes3.length > 5) ? nodes3[5].q1_5 : "",
			p3_q1_n5_6: (nodes3.length > 5) ? nodes3[5].q1_6 : "",
			p3_q1_n5_7: (nodes3.length > 5) ? nodes3[5].q1_7: "",
			p3_q2_n5: (nodes3.length > 5) ? nodes3[5].q2 : "",
			p3_q3_n5: (nodes3.length > 5) ? nodes3[5].q3 : "",
			p3_q4_n5_1: (nodes3.length > 5) ? nodes3[5].q4_1 : "",
			p3_q4_n5_2: (nodes3.length > 5) ? nodes3[5].q4_2: "",
			p3_q4_n5_3: (nodes3.length > 5) ? nodes3[5].q4_3 : "",
			p3_q4_n5_4: (nodes3.length > 5) ? nodes3[5].q4_4 : "",
			p3_q4_n5_5: (nodes3.length > 5) ? nodes3[5].q4_5 : "",
			p3_q4_n5_6: (nodes3.length > 5) ? nodes3[5].q4_6: "",
			p3_q4_n5_7: (nodes3.length > 5) ? nodes3[5].q4_7 : "",
			p3_q4_n5_8: (nodes3.length > 5) ? nodes3[5].q4_8 : "",
			p3_q4_n5_9: (nodes3.length > 5) ? nodes3[5].q4_9: "",
			p3_q4_n5_10: (nodes3.length > 5) ? nodes3[5].q4_10 : "",
			p3_q4_n5_11: (nodes3.length > 5) ? nodes3[5].q4_11 : "",
			p3_q4_n5_12: (nodes3.length > 5) ? nodes3[5].q4_12 : "",
			p3_q4_n5_13: (nodes3.length > 5) ? nodes3[5].q4_13 : "",
			p3_q5_n5: (nodes3.length > 5) ? nodes3[5].q5 : "",
			p3_link_n5: (nodes3.length > 5) ? p3_link[5] : "",

			p3_q6_1: (formalisierungForm2[0].checked) ? p3_q6_1 : "",
			p3_q6_2: (formalisierungForm2[1].checked) ? p3_q6_2 : "",
			p3_q6_3: (formalisierungForm2[2].checked) ? p3_q6_3 : "",
			p3_q6_4: (formalisierungForm2[3].checked) ? p3_q6_4 : "",
			p3_q6_5: (formalisierungForm2[4].checked) ? p3_q6_5 : "",
			p3_q6_6: (formalisierungForm2[5].checked) ? p3_q6_6 : "",
			p3_q6_7: (formalisierungForm2[6].checked) ? p3_q6_7 : "",

			p3_q7_1: (rahmenbedingungenForm2[0].checked) ? p3_q7_1 : "",
			p3_q7_2: (rahmenbedingungenForm2[1].checked) ? p3_q7_2 : "",
			p3_q7_3: (rahmenbedingungenForm2[2].checked) ? p3_q7_3 : "", 
			p3_q7_4: (rahmenbedingungenForm2[3].checked) ? p3_q7_4 : "",
			p3_q7_5: (rahmenbedingungenForm2[4].checked) ? p3_q7_5 : "",
			p3_q7_6: (rahmenbedingungenForm2[5].checked) ? p3_q7_6 : "",
			p3_q7_7: (rahmenbedingungenForm2[6].checked) ? p3_q7_7 : "",

			/*---part4---*/

			p4_name1: (nodes4.length > 1) ? p4_name[1] : "",
			p4_dd1_n1: (nodes4.length > 1) ? p4_dd1[1]: "",
			p4_dd2_n1: (nodes4.length > 1) ? p4_dd2[1] : "",
			p4_q1_n1_1: (nodes4.length > 1) ? nodes4[1].q1_1 : "",
			p4_q1_n1_2: (nodes4.length > 1) ? nodes4[1].q1_2 : "",
			p4_q1_n1_3: (nodes4.length > 1) ? nodes4[1].q1_3 : "",
			p4_q1_n1_4: (nodes4.length > 1) ? nodes4[1].q1_4 : "",
			p4_q1_n1_5: (nodes4.length > 1) ? nodes4[1].q1_5 : "",
			p4_q1_n1_6: (nodes4.length > 1) ? nodes4[1].q1_6 : "",
			p4_q1_n1_7: (nodes4.length > 1) ? nodes4[1].q1_7: "",
			p4_q2_n1: (nodes4.length > 1) ? nodes4[1].q2 : "",
			p4_q3_n1: (nodes4.length > 1) ? nodes4[1].q3 : "",
			p4_q4_n1_1: (nodes4.length > 1) ? nodes4[1].q4_1 : "",
			p4_q4_n1_2: (nodes4.length > 1) ? nodes4[1].q4_2: "",
			p4_q4_n1_3: (nodes4.length > 1) ? nodes4[1].q4_3 : "",
			p4_q4_n1_4: (nodes4.length > 1) ? nodes4[1].q4_4 : "",
			p4_q4_n1_5: (nodes4.length > 1) ? nodes4[1].q4_5 : "",
			p4_q4_n1_6: (nodes4.length > 1) ? nodes4[1].q4_6: "",
			p4_q4_n1_7: (nodes4.length > 1) ? nodes4[1].q4_7 : "",
			p4_q4_n1_8: (nodes4.length > 1) ? nodes4[1].q4_8 : "",
			p4_q4_n1_9: (nodes4.length > 1) ? nodes4[1].q4_9: "",
			p4_q4_n1_10: (nodes4.length > 1) ? nodes4[1].q4_10 : "",
			p4_q4_n1_11: (nodes4.length > 1) ? nodes4[1].q4_11 : "",
			p4_q4_n1_12: (nodes4.length > 1) ? nodes4[1].q4_12 : "",
			p4_q4_n1_13: (nodes4.length > 1) ? nodes4[1].q4_13 : "",
			p4_q5_n1: (nodes4.length > 1) ? nodes4[1].q5 : "",
			p4_link_n1: (nodes4.length > 1) ? p4_link[1] : "",

			p4_name2: (nodes4.length > 2) ? p4_name[2] : "",
			p4_dd1_n2: (nodes4.length > 2) ? p4_dd1[2]: "",
			p4_dd2_n2: (nodes4.length > 2) ? p4_dd2[2] : "",
			p4_q1_n2_1: (nodes4.length > 2) ? nodes4[2].q1_1 : "",
			p4_q1_n2_2: (nodes4.length > 2) ? nodes4[2].q1_2 : "",
			p4_q1_n2_3: (nodes4.length > 2) ? nodes4[2].q1_3 : "",
			p4_q1_n2_4: (nodes4.length > 2) ? nodes4[2].q1_4 : "",
			p4_q1_n2_5: (nodes4.length > 2) ? nodes4[2].q1_5 : "",
			p4_q1_n2_6: (nodes4.length > 2) ? nodes4[2].q1_6 : "",
			p4_q1_n2_7: (nodes4.length > 2) ? nodes4[2].q1_7: "",
			p4_q2_n2: (nodes4.length > 2) ? nodes4[2].q2 : "",
			p4_q3_n2: (nodes4.length > 2) ? nodes4[2].q3 : "",
			p4_q4_n2_1: (nodes4.length > 2) ? nodes4[2].q4_1 : "",
			p4_q4_n2_2: (nodes4.length > 2) ? nodes4[2].q4_2: "",
			p4_q4_n2_3: (nodes4.length > 2) ? nodes4[2].q4_3 : "",
			p4_q4_n2_4: (nodes4.length > 2) ? nodes4[2].q4_4 : "",
			p4_q4_n2_5: (nodes4.length > 2) ? nodes4[2].q4_5 : "",
			p4_q4_n2_6: (nodes4.length > 2) ? nodes4[2].q4_6: "",
			p4_q4_n2_7: (nodes4.length > 2) ? nodes4[2].q4_7 : "",
			p4_q4_n2_8: (nodes4.length > 2) ? nodes4[2].q4_8 : "",
			p4_q4_n2_9: (nodes4.length > 2) ? nodes4[2].q4_9: "",
			p4_q4_n2_10: (nodes4.length > 2) ? nodes4[2].q4_10 : "",
			p4_q4_n2_11: (nodes4.length > 2) ? nodes4[2].q4_11 : "",
			p4_q4_n2_12: (nodes4.length > 2) ? nodes4[2].q4_12 : "",
			p4_q4_n2_13: (nodes4.length > 2) ? nodes4[2].q4_13 : "",
			p4_q5_n2: (nodes4.length > 2) ? nodes4[2].q5 : "",
			p4_link_n2: (nodes4.length > 2) ? p4_link[2] : "",

			p4_name3: (nodes4.length > 3) ? p4_name[3] : "",
			p4_dd1_n3: (nodes4.length > 3) ? p4_dd1[3]: "",
			p4_dd2_n3: (nodes4.length > 3) ? p4_dd2[3] : "",
			p4_q1_n3_1: (nodes4.length > 3) ? nodes4[3].q1_1 : "",
			p4_q1_n3_2: (nodes4.length > 3) ? nodes4[3].q1_2 : "",
			p4_q1_n3_3: (nodes4.length > 3) ? nodes4[3].q1_3 : "",
			p4_q1_n3_4: (nodes4.length > 3) ? nodes4[3].q1_4 : "",
			p4_q1_n3_5: (nodes4.length > 3) ? nodes4[3].q1_5 : "",
			p4_q1_n3_6: (nodes4.length > 3) ? nodes4[3].q1_6 : "",
			p4_q1_n3_7: (nodes4.length > 3) ? nodes4[3].q1_7: "",
			p4_q2_n3: (nodes4.length > 3) ? nodes4[3].q2 : "",
			p4_q3_n3: (nodes4.length > 3) ? nodes4[3].q3 : "",
			p4_q4_n3_1: (nodes4.length > 3) ? nodes4[3].q4_1 : "",
			p4_q4_n3_2: (nodes4.length > 3) ? nodes4[3].q4_2: "",
			p4_q4_n3_3: (nodes4.length > 3) ? nodes4[3].q4_3 : "",
			p4_q4_n3_4: (nodes4.length > 3) ? nodes4[3].q4_4 : "",
			p4_q4_n3_5: (nodes4.length > 3) ? nodes4[3].q4_5 : "",
			p4_q4_n3_6: (nodes4.length > 3) ? nodes4[3].q4_6: "",
			p4_q4_n3_7: (nodes4.length > 3) ? nodes4[3].q4_7 : "",
			p4_q4_n3_8: (nodes4.length > 3) ? nodes4[3].q4_8 : "",
			p4_q4_n3_9: (nodes4.length > 3) ? nodes4[3].q4_9: "",
			p4_q4_n3_10: (nodes4.length > 3) ? nodes4[3].q4_10 : "",
			p4_q4_n3_11: (nodes4.length > 3) ? nodes4[3].q4_11 : "",
			p4_q4_n3_12: (nodes4.length > 3) ? nodes4[3].q4_12 : "",
			p4_q4_n3_13: (nodes4.length > 3) ? nodes4[3].q4_13 : "",
			p4_q5_n3: (nodes4.length > 3) ? nodes4[3].q5 : "",
			p4_link_n3: (nodes4.length > 3) ? p4_link[3] : "",

			p4_name4: (nodes4.length > 4) ? p4_name[4] : "",
			p4_dd1_n4: (nodes4.length > 4) ? p4_dd1[4]: "",
			p4_dd2_n4: (nodes4.length > 4) ? p4_dd2[4] : "",
			p4_q1_n4_1: (nodes4.length > 4) ? nodes4[4].q1_1 : "",
			p4_q1_n4_2: (nodes4.length > 4) ? nodes4[4].q1_2 : "",
			p4_q1_n4_3: (nodes4.length > 4) ? nodes4[4].q1_3 : "",
			p4_q1_n4_4: (nodes4.length > 4) ? nodes4[4].q1_4 : "",
			p4_q1_n4_5: (nodes4.length > 4) ? nodes4[4].q1_5 : "",
			p4_q1_n4_6: (nodes4.length > 4) ? nodes4[4].q1_6 : "",
			p4_q1_n4_7: (nodes4.length > 4) ? nodes4[4].q1_7: "",
			p4_q2_n4: (nodes4.length > 4) ? nodes4[4].q2 : "",
			p4_q3_n4: (nodes4.length > 4) ? nodes4[4].q3 : "",
			p4_q4_n4_1: (nodes4.length > 4) ? nodes4[4].q4_1 : "",
			p4_q4_n4_2: (nodes4.length > 4) ? nodes4[4].q4_2: "",
			p4_q4_n4_3: (nodes4.length > 4) ? nodes4[4].q4_3 : "",
			p4_q4_n4_4: (nodes4.length > 4) ? nodes4[4].q4_4 : "",
			p4_q4_n4_5: (nodes4.length > 4) ? nodes4[4].q4_5 : "",
			p4_q4_n4_6: (nodes4.length > 4) ? nodes4[4].q4_6: "",
			p4_q4_n4_7: (nodes4.length > 4) ? nodes4[4].q4_7 : "",
			p4_q4_n4_8: (nodes4.length > 4) ? nodes4[4].q4_8 : "",
			p4_q4_n4_9: (nodes4.length > 4) ? nodes4[4].q4_9: "",
			p4_q4_n4_10: (nodes4.length > 4) ? nodes4[4].q4_10 : "",
			p4_q4_n4_11: (nodes4.length > 4) ? nodes4[4].q4_11 : "",
			p4_q4_n4_12: (nodes4.length > 4) ? nodes4[4].q4_12 : "",
			p4_q4_n4_13: (nodes4.length > 4) ? nodes4[4].q4_13 : "",
			p4_q5_n4: (nodes4.length > 4) ? nodes4[4].q5 : "",
			p4_link_n4: (nodes4.length > 4) ? p4_link[4] : "",

			p4_name5: (nodes4.length > 5) ? p4_name[5] : "",
			p4_dd1_n5: (nodes4.length > 5) ? p4_dd1[5]: "",
			p4_dd2_n5: (nodes4.length > 5) ? p4_dd2[5] : "",
			p4_q1_n5_1: (nodes4.length > 5) ? nodes4[5].q1_1 : "",
			p4_q1_n5_2: (nodes4.length > 5) ? nodes4[5].q1_2 : "",
			p4_q1_n5_3: (nodes4.length > 5) ? nodes4[5].q1_3 : "",
			p4_q1_n5_4: (nodes4.length > 5) ? nodes4[5].q1_4 : "",
			p4_q1_n5_5: (nodes4.length > 5) ? nodes4[5].q1_5 : "",
			p4_q1_n5_6: (nodes4.length > 5) ? nodes4[5].q1_6 : "",
			p4_q1_n5_7: (nodes4.length > 5) ? nodes4[5].q1_7: "",
			p4_q2_n5: (nodes4.length > 5) ? nodes4[5].q2 : "",
			p4_q3_n5: (nodes4.length > 5) ? nodes4[5].q3 : "",
			p4_q4_n5_1: (nodes4.length > 5) ? nodes4[5].q4_1 : "",
			p4_q4_n5_2: (nodes4.length > 5) ? nodes4[5].q4_2: "",
			p4_q4_n5_3: (nodes4.length > 5) ? nodes4[5].q4_3 : "",
			p4_q4_n5_4: (nodes4.length > 5) ? nodes4[5].q4_4 : "",
			p4_q4_n5_5: (nodes4.length > 5) ? nodes4[5].q4_5 : "",
			p4_q4_n5_6: (nodes4.length > 5) ? nodes4[5].q4_6: "",
			p4_q4_n5_7: (nodes4.length > 5) ? nodes4[5].q4_7 : "",
			p4_q4_n5_8: (nodes4.length > 5) ? nodes4[5].q4_8 : "",
			p4_q4_n5_9: (nodes4.length > 5) ? nodes4[5].q4_9: "",
			p4_q4_n5_10: (nodes4.length > 5) ? nodes4[5].q4_10 : "",
			p4_q4_n5_11: (nodes4.length > 5) ? nodes4[5].q4_11 : "",
			p4_q4_n5_12: (nodes4.length > 5) ? nodes4[5].q4_12 : "",
			p4_q4_n5_13: (nodes4.length > 5) ? nodes4[5].q4_13 : "",
			p4_q5_n5: (nodes4.length > 5) ? nodes4[5].q5 : "",
			p4_link_n5: (nodes4.length > 5) ? p4_link[5] : "",

			p4_q6_1: (formalisierungForm3[0].checked) ? p4_q6_1 : "",
			p4_q6_2: (formalisierungForm3[1].checked) ? p4_q6_2 : "",
			p4_q6_3: (formalisierungForm3[2].checked) ? p4_q6_3 : "",
			p4_q6_4: (formalisierungForm3[3].checked) ? p4_q6_4 : "",
			p4_q6_5: (formalisierungForm3[4].checked) ? p4_q6_5 : "",
			p4_q6_6: (formalisierungForm3[5].checked) ? p4_q6_6 : "",
			p4_q6_7: (formalisierungForm3[6].checked) ? p4_q6_7 : "",

			p4_q7_1: (rahmenbedingungenForm3[0].checked) ? p4_q7_1 : "",
			p4_q7_2: (rahmenbedingungenForm3[1].checked) ? p4_q7_2 : "",
			p4_q7_3: (rahmenbedingungenForm3[2].checked) ? p4_q7_3 : "", 
			p4_q7_4: (rahmenbedingungenForm3[3].checked) ? p4_q7_4 : "",
			p4_q7_5: (rahmenbedingungenForm3[4].checked) ? p4_q7_5 : "",
			p4_q7_6: (rahmenbedingungenForm3[5].checked) ? p4_q7_6 : "",
			p4_q7_7: (rahmenbedingungenForm3[6].checked) ? p4_q7_7 : ""
            });            
            
            checked = false;
            //bw.style.display = "none";
			console.log("aktuelle Slide: " + currSlide);

            var sf = document.getElementById("submitForm");
            var sb = document.getElementById("submitButton");
            var nd = document.getElementById("NextDiv");
            sf.style.display = "block";
            nd.style.display = "none";
            
			/*var motivationText = d3.select("svg").append("text")
              .attr("class", "slideText")
              .attr("id", "motivationText")
              .attr("x", center - (textWidth / 2) + 50)
              .attr("y", text_offset_top + 40)
              .text("Danke, dass Sie an dieser Umfrage teilgenommen haben, bitte klicken Sie \"Weiter\" um die Umfrage zu beenden.")
              .call(wrap, textWidth);
              */
			  
            // Release window close-prevention
            unhook();
          //}
        }
      }
      
      // Detect Internet Explorer
      var ie = (function(){
        var undef,
            v = 3,
            div = document.createElement('div'),
            all = div.getElementsByTagName('i');
        while (
            div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
            all[0]
        );
        return v > 4 ? v : undef;
      }());

      function isIE () {
        return (ie < 10);
      }
      
    </script>
 
    <div class="input-group" display="none" id="name_input" method="get" onsubmit="addFriend()">
      <input type="text" id="friendNameID" name="friendName" class="form-control" placeholder="Name" size="10" >
      <button type="submit" class="btn btn-default" position="inline" value="Enter" onclick="addFriend()">Hinzufügen
    </div>

	<div class="input-group"  id="teilnehmercode" method="get">
		<form id="teilnehmer" >
		<input type="text" id="teilnehmercodeInput" placeholder="Bitte geben Sie Ihren individuellen Teilnehmercode ein" size="60">
		<button type="button" class="btn btn-default" position="inline" onclick="saveValue()">Bestätigen</button>
		</form>
	</div> 
	
	<div class="input-group no-wrap" display="none" id="artKommunikation" method="get">
      <form id="artDerKommunikation" display="none">
		<input type="checkbox" name="artDerKommunikation" value="1"><span class="questionText"> persönliches Gespräch</span><br>
        <input type="checkbox" name="artDerKommunikation" value="2"><span class="questionText"> E-Mail</span><br>
        <input type="checkbox" name="artDerKommunikation" value="3"><span class="questionText"> Schulische digitale Plattform <br><span class="einrueckung">(Intranet/Lernmanagementsystem, z.B. moodle)</span></span><br>
        <input type="checkbox" name="artDerKommunikation" value="4"><span class="questionText"> soziale Medien/ Onlineplattform</span><br>
        <input type="checkbox" name="artDerKommunikation" value="5"><span class="questionText"> Messenger (WhatsApp, Threema, o.ä.)</span><br>
		<input type="checkbox" name="artDerKommunikation" value="6"><span class="questionText"> Telefon </span><br>
		<input type="checkbox" name="artDerKommunikation" value="7"><span class="questionText"> anders</span>
      </form>
    </div>
	
	<div class="input-group" display="none" id="themaErhalteneInfos" method="get">
		<form id="erhInfo" display="none">
			<input type="text" id="myText" placeholder="Bitte nennen Sie Stichworte" size="30">
		</form>
	</div>
	
	<div class="input-group no-wrap" display="none" id="digiSchulentwicklung" method="get">
	  <form id="digitaleSchulentwicklung" display="none">
		<input type="radio" name="digitaleSchulentwicklung" value="1"><span class="questionText"> Technische Ausstattung der Schule<br> <span class="einrueckung">(Soft- und Hardware, digitale Medien und Werkzeuge, IT-Infrastruktur...)</span></span><br>
		<input type="radio" name="digitaleSchulentwicklung" value="2"><span class="questionText"> Fortbildungen/ Schulungen für Lehrkräfte</span><br>
		<input type="radio" name="digitaleSchulentwicklung" value="3"><span class="questionText"> Unterrichtsentwicklung mit digitalen Medien</span><br>
		<input type="radio" name="digitaleSchulentwicklung" value="4"><span class="questionText"> Organisatorische Bedingungen<br><span class="einrueckung">(Schulprofil, Steuergruppen, Schulleitungshandeln,...)</span></span><br>
		<input type="radio" name="digitaleSchulentwicklung" value="5"><span class="questionText"> Kommunikation (Öffentlichkeitsarbeit, Elternarbeit, Schülerpartizipation,...)</span><br>
		<input type="radio" name="digitaleSchulentwicklung" value="6"><span class="questionText"> Gesamtstrategie Digitalisierung (z.B. Medienkonzept,...)</span>
	  </form>
    </div>
	
	<div class="input-group no-wrap" display="none" id="funktionenPersonen" method="get">
	  <form id="funktionenDerPersonen" display="none">
		<input type="checkbox" name="funktionenDerPersonen" value="1"><span class="questionText"> (erweiterte) Schulleitung</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="2"><span class="questionText"> Didaktische Leitung</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="3"><span class="questionText"> Steuergruppe oder AG "Schulentwicklung"</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="4"><span class="questionText"> AG "digitale Medien,"<br><span class="einrueckung">"Medienkonzept" oder ähnliches</span></span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="5"><span class="questionText"> Medienbeauftragte*r / IT-Koordinator*in</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="6"><span class="questionText"> Vorsitz der Fachkonferenz</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="7"><span class="questionText"> Mitglied der Schulkonferenz</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="8"><span class="questionText"> Fortbildungsbeauftragte*r</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="9"><span class="questionText"> Mitglied des Lehrrates</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="10"><span class="questionText"> Schülervertretung (inkl. Medienscouts)</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="11"><span class="questionText"> Nicht-schulisch (z.B. Schulträger,<br><span class="einrueckung">Universität, Beratung oder ähnliches)</span></span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="12"><span class="questionText"> Weiß ich nicht</span><br>
		<input type="checkbox" name="funktionenDerPersonen" value="13"><span class="questionText"> Andere (Leitungs-) Funktion<br><span class="einrueckung">und zwar:</span></span>
		<input type="text" id="weitereFunktionen" name="stichworte" class="form-control" placeholder="Bitte nennen Sie Stichworte" size="30" >
	  </form>
    </div>
	
	<div class="input-group" display="none" id="wahrgenLernmöglichkeiten" method="get">
      <form id="subjWahrLernm" display="none">
		<input type="radio" name="subjWahrLernm" value="1"><span class="questionText"> Ja</span><br>
		<input type="radio" name="subjWahrLernm" value="2"><span class="questionText"> Eher Ja </span><br>
		<input type="radio" name="subjWahrLernm" value="3"><span class="questionText"> Eher Nein</span><br>
		<input type="radio" name="subjWahrLernm" value="4"><span class="questionText"> Nein </span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="gradFormalisierung" method="get">
		<form id="gradDerFormalisierung" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie in den letzten drei Monaten Informationen über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien zum Thema Digitalisierung der Schule an <b>Personen an Ihrer Schule</b> weitergegeben haben: Was trifft auf die Rahmenbedingungen der Informationsweitergabe zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="gradDerFormalisierung" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="2"><span class="questionText"> geschieht im Rahmen der ohnehin vorgegebenen Treffen (z.B. Lehrerkonferenzen, Fachkonferenzen) </span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="3"><span class="questionText"> geschieht eher informell und beiläufig</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="4"><span class="questionText"> es gibt dafür vorgesehene Arbeitszeiten/Treffen</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="5"><span class="questionText"> geschieht über eine Lernplattform (z.B. moodle), auf die alle Lehrkräfte zugreifen können</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="6"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="7"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="8"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="gradDerFormalisierung" value="9"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="gradFormalisierung2" method="get">
		<form id="gradDerFormalisierung2" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie mit <b>Personen an Ihrer Schule</b> in den letzten drei Monaten arbeitsteilig an bestehenden oder neuen Strategien oder Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet haben: Was trifft auf die Rahmenbedingungen der Zusammenarbeit zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="2"><span class="questionText"> es gibt dafür eigens vorgesehene Arbeitszeiten und Räume <br><span class="einrueckung">(außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)</span></span><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="3"><span class="questionText"> es werden bestimmte Methoden und Formate genutzt</span><br> 
			<input type="checkbox" name="gradDerFormalisierung2" value="4"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="5"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="6"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="gradDerFormalisierung2" value="7"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="gradFormalisierung3" method="get">
		<form id="gradDerFormalisierung3" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie mit <b>Personen an Ihrer Schule</b> in den letzten drei Monaten gemeinsam Strategien oder Maßnahmen zur Digitalisierung an der Schule entwickelt haben: Was trifft auf die Rahmenbedingungen der gemeinsamen Entwicklung zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="2"><span class="questionText"> es gibt dafür eigens vorgesehene Arbeitszeiten und Räume <br><span class="einrueckung">(außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)</span></span><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="3"><span class="questionText"> es werden bestimmte Methoden und Formate genutzt</span><br> 
			<input type="checkbox" name="gradDerFormalisierung3" value="4"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="5"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="6"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="gradDerFormalisierung3" value="7"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="rahmenbedingungenInfoweitergabe1" method="get">
		<form id="rahmenDerInfoweitergabe1" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie in den letzten drei Monaten Informationen über bestehende oder neue Praxisbeispiele, Maßnahmen oder Strategien zum Thema Digitalisierung der Schule an <b>Personen außerhalb Ihrer Schule</b> weitergegeben haben: Was trifft auf die Rahmenbedingungen der Informationsweitergabe zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="2"><span class="questionText"> geschieht im Rahmen der ohnehin vorgegebenen Treffen (z.B. Lehrerkonferenzen, Fachkonferenzen) </span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="3"><span class="questionText"> geschieht eher informell und beiläufig</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="4"><span class="questionText"> es gibt dafür vorgesehene Arbeitszeiten/Treffen</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="5"><span class="questionText"> geschieht über eine Lernplattform (z.B. moodle), auf die alle Lehrkräfte zugreifen können</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="6"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="7"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="8"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe1" value="9"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="rahmenbedingungenInfoweitergabe2" method="get">
		<form id="rahmenDerInfoweitergabe2" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie mit <b>Personen außerhalb Ihrer Schule</b> in den letzten drei Monaten arbeitsteilig an bestehenden oder neuen Strategien oder Maßnahmen zur Digitalisierung an der Schule zusammengearbeitet haben: Was trifft auf die Rahmenbedingungen der Zusammenarbeit zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="2"><span class="questionText"> es gibt dafür eigens vorgesehene Arbeitszeiten und Räume <br><span class="einrueckung">(außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)</span></span><<br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="3"><span class="questionText"> es werden bestimmte Methoden und Formate genutzt</span><br> 
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="4"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="5"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="6"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe2" value="7"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>
	
	<div class="input-group" display="none" id="rahmenbedingungenInfoweitergabe3" method="get">
		<form id="rahmenDerInfoweitergabe3" display="none">
			<span class="slideText">Wenn Sie daran denken, wie Sie mit <b>Personen außerhalb Ihrer Schule</b> in den letzten drei Monaten gemeinsam Strategien oder Maßnahmen zur Digitalisierung an der Schule entwickelt haben: Was trifft auf die Rahmenbedingungen der gemeinsamen Entwicklung zu?</span><br><br>
			<span class="slideText">(Mehrfachnennungen möglich):</span><br><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="1"><span class="questionText"> geschieht auf regelmäßiger Basis</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="2"><span class="questionText"> es gibt dafür eigens vorgesehene Arbeitszeiten und Räume <br><span class="einrueckung">(außerhalb der ohnehin vorgegebenen Treffen wie z.B. Lehrerkonferenzen, Fachkonferenzen)</span></span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="3"><span class="questionText"> es werden bestimmte Methoden und Formate genutzt</span><br> 
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="4"><span class="questionText"> geschieht im Rahmen der Netzwerktreffen oder Veranstaltungen des Netzwerks</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="5"><span class="questionText"> geschieht ausschließlich aus Eigeninitiative der beteiligten Personen </span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="6"><span class="questionText"> wird von der Schulleitung gefördert</span><br>
			<input type="checkbox" name="rahmenDerInfoweitergabe3" value="7"><span class="questionText"> wird von der Schulleitung angeordnet</span>
		</form>
	</div>

    <div class="popop_box" id="nonresponse_box">
      <div class="popup_box" id="popup">
            <p class="popup_text">Sie haben diese Frage noch nicht beantwortet. Es wäre sehr hilfreich für diese Umfrage, wenn sie dies täten. Bitte beantworten Sie diese Frage oder klicken Sie erneut auf 'Weiter', wenn Sie dies nicht tun möchten.</p>
            <button class="btn btn-default" onclick="closePopup()">Schließen</button>
      </div>
    </div>
	
	<div class="popop_box" id="noID_box">
      <div class="popup_box" id="noIdPopup">
            <p class="popup_text">Bitte geben Sie zuerst Ihren individuellen Teilnehmercode ein, den Sie mit der Einladungsmail erhalten haben. Klicken Sie dann auf 'Bestätigen', um mit der Befragung zu starten.</p>
            <button class="btn btn-default" onclick="closeNoIDPopup()">Schließen</button>
      </div>
    </div>

    <div class="popop_box" id="onlyone_box">
      <div class="popup_box" id="onlyOnePopup">
            <p class="popup_text">Bitte geben Sie immer nur ein Kürzel ein.</p>
            <button class="btn btn-default" onclick="closeOnlyOnePopup()">Schließen</button>
      </div>
    </div>

    <div class="popop_box" id="fewFriends_box">
      <div class="popup_box" id="friendPopup">
            <p class="popup_text">Sie haben die Möglichkeit, hier bis zu 5 Personen anzugeben. Bisher haben Sie noch keine 5 Personen genannt. Wenn Sie weitere Personen nennen möchten, geben Sie bitte die Abkürzungen in das Feld ein und klicken "Hinzufügen". Andernfalls klicken Sie bitte "Weiter", um fortzufahren.</p>
            <button class="btn btn-default" onclick="closeFriendPopup()">Schließen</button>
      </div>
    </div>

    <div class="popop_box" id="fewDragged_box">
      <div class="popup_box" id="dragPopup">
            <p class="popup_text">Sie haben diese Antwort nicht für alle Personen in ihrem Netzwerk beantwortet. Es wäre sehr hilfreich für diese Umfrage, wenn sie dies täten. Bitte beantworten Sie diese Frage auch für die übrigen Personen oder klicken Sie erneut auf 'Weiter', wenn Sie dies nicht tun möchten.</p>
            <button class="btn btn-default" onclick="closeDragPopup()">Schließen</button>
      </div>
    </div>

    <div id="NextDiv">
      <input type="button" 
        class="btn btn-default" 
        value="Weiter"	
        id="Next"
        onclick="showNext()" />
    </div>
    
	<!--was geschieht hier?-->
    <div id="submitForm">
      <form id="customapplication" action="<?php echo $_POST  ['returnpage']; ?>" method="post">
        <input type="hidden" name="sh" value="<?php echo $_POST['sh']; ?>"/>
        <input type="hidden" name="lsi" value="<?php echo $_POST['lsi']; ?>"/>
        <input type="hidden" name="pli" value="<?php echo $_POST['pli']; ?>"/>
        <input type="hidden" name="spi" value="<?php echo $_POST['spi']; ?>"/>
        <input type="hidden" name="aqi" value="<?php echo $_POST['aqi']; ?>"/>
        <input type="hidden" name="cqi" value="<?php echo $_POST['cqi']; ?>"/>
        <input type="hidden" name="KeyValue" value="<?php echo $_POST['KeyValue']; ?>"/>
        <input type="hidden" name="InterviewID" value="<?php echo $_POST['InterviewId']; ?>"/>
        <input type="hidden" name="Lmr" value="<?php echo $_POST['Lmr']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['statusvarname1']; ?>" value="<?php echo $_POST['statusvarvalue1']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['varname1']; ?>" id="qu1_id" value=""/>
        <input type="hidden" id="nomem" name="nomem" value="<?php echo $_POST['nomem']; ?>"/>
        <input name="<?php echo $_POST['nextvarname']; ?>" id="submitButton" class="btn btn-default" type="submit" value="Senden"/>
      </form>
    </div>
 
    <script type="text/javascript">
        $("#Next").css("left",window.innerWidth * .8);
        $("#submitButton").css("left",window.innerWidth * .8);
    </script>
	</div>
	
	<footer id="footer">
		<a href="datenschutzerklärung.html" target="_blank">Datenschutzerklärung anzeigen</a>
	</footer>
	
  </body>
</html>
