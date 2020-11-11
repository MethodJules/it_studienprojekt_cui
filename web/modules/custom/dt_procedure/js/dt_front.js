(function ($, Drupal, drupalSettings) {
    var initialized;
    base_path = drupalSettings.baseUrl;

    function init() {
        if(!initialized) {
            initialized = true;
            console.log('js');
            // Your custom JS.
            var w = 1200;
            var h = 1000;
            div = 'visualization';
            //Add svg tag to div container
            var svg = d3.select('#' + div).append("svg")
                .attr("preserveAspectRatio", "xMinYMin meet")
                .attr("viewBox", "0 0 1400 940")

                .classed("svg-container", true)
                .classed("svg-content-responsive", true);
            //.on("click", explicitlyPosition);
            //.tick();

            //Vorbereitung
            var vorbereitungGroup = svg.append('g');
            var vorbereitung = vorbereitungGroup.append('rect')
                .attr('id', 'vorbereitung')
                .attr('x', 1)
                .attr('y', 1)
                .attr('width', 1295)
                .attr('height', 100)
                .style('fill', '#595959')
                .on('click', function() {handleClick("vorbereitung")});
            var vorbereitungText = vorbereitungGroup.append('text')
                .attr('x', 500)
                .attr('y', 70)
                .text('Vorbereitung')
                .attr('font-family', 'sans-serif')
                .attr('font-size', '55px')
                .style('fill', 'white' )
                .on('click', function() {handleClick("vorbereitung")});


            //Projektmanagement
            var projectManagementGroup = svg.append('g');
            var projectManagementLabelRect = projectManagementGroup.append("rect")
                .attr("x", 1)
                .attr("y", 830)
                .attr("width", 1295)
                .attr("height", 100)
                .style('fill', '#595959')
                .on('click', function() {handleClick("projektmanagement")});



            var projectManagementLabelRect = projectManagementGroup.append("text")
                .attr("x", 405)
                .attr("y", 894)
                .text( "Projektmanagement")
                .attr("font-family", "sans-serif")
                .attr("font-size", "55px")
                .attr("fill", "white")
                .on('click', function() {handleClick("projektmanagement")});




            //Problemraum
            var problemraumGroup = svg.append('g');
            var problemraumLabelRect = problemraumGroup.append("rect")
                .attr("x", 1)
                .attr("y", 103)
                .attr("width", 430)
                .attr("height", 60)
                .style('fill', '#595959');

            var problemraumLabel = problemraumGroup.append("text")
                .attr("x", 120)
                .attr("y", 145)
                .text( "Problemraum")
                .attr("font-family", "sans-serif")
                .attr("font-size", "35px")
                .attr("fill", "white");
            //erste Phase
            var erstePhaseDivergentGroup = problemraumGroup.append('g');
            var erstePhaseDivergent = erstePhaseDivergentGroup.append("rect")
                .attr("id", "erstePhaseDivergent")
                .attr("x", 1)
                .attr("y", 227)
                .attr("width", 214)
                .attr("height", 600)
                .style("fill", "#ad91be")
                .on('click', function() {handleClick("problemraum", "divergent")});


            var erstePhaseDivergentLabelRect = erstePhaseDivergentGroup.append("rect")
                .attr("x", 1)
                .attr("y", 165)
                .attr("width", 213)
                .attr("height", 60)
                .style("fill", "#ad91be")
                .on('click', function() {handleClick("problemraum", "divergent")});

            var erstePhaseDivergentLabel = erstePhaseDivergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 70)
                .attr("y", 190)
                .html("Problem")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            var erstePhaseDivergentLabel_2 = erstePhaseDivergentGroup.append("text")
                .attr("dy", "1em")
                .attr("x", 65)
                .attr("y", 193)
                .html("definieren")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");


            var erstePhaseKonvergentGroup = problemraumGroup.append('g');
            //erste Phase konvergent
            var erstePhaseKonvergent = erstePhaseKonvergentGroup.append("rect")
                .attr("id", "erstePhaseKonvergent")
                .attr("x", 215)
                .attr("y", 227)
                .attr("width", 216)
                .attr("height", 600)
                .style("fill", "#ad91be")
                .on('click', function() {handleClick("problemraum", "konvergent")});


            var erstePhaseKonvergentLabelRect = erstePhaseKonvergentGroup.append("rect")
                .attr("x", 216)
                .attr("y", 165)
                .attr("width", 215)
                .attr("height", 60)
                .style("fill", "#ad91be")
                .on('click', function() {handleClick("problemraum", "konvergent")});



            var erstePhaseKonvergentLabel = erstePhaseKonvergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 250)
                .attr("y", 200)
                .html("Recherchieren")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            //=================================
            var loesungsraumGroup = svg.append('g');

            var loesungsraumLabelRect = problemraumGroup.append("rect")
                .attr("x", 433)
                .attr("y", 103)
                .attr("width", 430)
                .attr("height", 60)
                .style('fill', '#595959');

            var loesungsraumLabel = problemraumGroup.append("text")
                .attr("x", 552)
                .attr("y", 145)
                .text( "Lösungsraum")
                .attr("font-family", "sans-serif")
                .attr("font-size", "35px")
                .attr("fill", "white");

            var zweitePhaseDivergentGroup = loesungsraumGroup.append('g');
            //zweitePhase divergent
            var zweitePhaseDivergent = zweitePhaseDivergentGroup.append("rect")
                .attr("id", "zweitePhaseDivergent")
                .attr("x", 433)
                .attr("y", 227)
                .attr("width", 215)
                .attr("height", 600)
                .style("fill", "#7a9bb9")
                .on('click', function() {handleClick("lösungsraum", "divergent")});

            var zweitePhaseDivergentLabelRect = zweitePhaseDivergentGroup.append("rect")
                .attr("x", 433)
                .attr("y", 165)
                .attr("width", 214)
                .attr("height", 60)
                .style("fill", "#7a9bb9")
                .on('click', function() {handleClick("lösungsraum", "divergent")});



            var zweitePhaseDivergentLabel = zweitePhaseDivergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 515)
                .attr("y", 190)
                .html("Ideen")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            var zweitePhaseDivergentLabel_2 = zweitePhaseDivergentGroup.append("text")
                .attr("dy", "1em")
                .attr("x", 490)
                .attr("y", 193)
                .html("generieren")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            //zweitePhase konvergent
            var zweitePhaseKonvergentGroup = loesungsraumGroup.append('g');
            var zweitePhaseKonvergent = zweitePhaseKonvergentGroup.append("rect")
                .attr("id", "zweitePhaseKonvergent")
                .attr("x", 648)
                .attr("y", 227)
                .attr("width", 216)
                .attr("height", 600)
                .style("fill", "#7a9bb9")
                .on('click', function() {handleClick("lösungsraum", "konvergent")});


            var zweitePhaseKonvergentLabelRect = zweitePhaseKonvergentGroup.append("rect")
                .attr("x", 649)
                .attr("y", 165)
                .attr("width", 215)
                .attr("height", 60)
                .style("fill", "#7a9bb9")
                .on('click', function() {handleClick("lösungsraum", "konvergent")});



            var zweitePhaseKonvergentLabel = zweitePhaseKonvergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 729)
                .attr("y", 190)
                .html("Ideen")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            var zweitePhaseKonvergentLabel_2 = zweitePhaseKonvergentGroup.append("text")
                .attr("dy", "1em")
                .attr("x", 700)
                .attr("y", 193)
                .html("ausarbeiten")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");
             //======================================

            var implementierungsraumGroup = svg.append('g');

            var implementierungsraumLabelRect = problemraumGroup.append("rect")
                .attr("x", 866)
                .attr("y", 103)
                .attr("width", 430)
                .attr("height", 60)
                .style('fill', '#595959');

            var implementierungsraumLabel = problemraumGroup.append("text")
                .attr("x", 890)
                .attr("y", 145)
                .text( "Implementierungsraum")
                .attr("font-family", "sans-serif")
                .attr("font-size", "35px")
                .attr("fill", "white");

            var drittePhaseDivergentGroup = implementierungsraumGroup.append('g');
            //Dritte Phase Divergent
            var drittePhaseDivergent = drittePhaseDivergentGroup.append("rect")
                .attr("id", "drittePhaseDivergent")
                .attr("x", 866)
                .attr("y", 227)
                .attr("width", 216)
                .attr("height", 600)
                .style("fill", "#70c46f")
                .on('click', function() {handleClick("implementierungsraum", "divergent")});


            var drittePhaseDivergentLabelRect = drittePhaseDivergentGroup.append("rect")
                .attr("x", 866)
                .attr("y", 165)
                .attr("width", 214)
                .attr("height", 60)
                .style("fill", "#70c46f")
                .on('click', function() {handleClick("implementierungsraum", "divergent")});



            var drittePhaseDivergentLabel = drittePhaseDivergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 920)
                .attr("y", 190)
                .html("Prototypen")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            var drittePhaseDivergentLabel_2 = drittePhaseDivergentGroup.append("text")
                .attr("dy", "1em")
                .attr("x", 917)
                .attr("y", 193)
                .html("ausarbeiten")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");


            //Dritte Phase konvergent
            var drittePhaseKonvergentGroup = implementierungsraumGroup.append('g');
            var drittePhaseKonvergent = drittePhaseKonvergentGroup.append("rect")
                .attr("id", "drittePhaseKonvergent")
                .attr("x", 1081)
                .attr("y", 227)
                .attr("width", 215)
                .attr("height", 600)
                .style("fill", "#70c46f")
                .on('click', function() {handleClick("implementierungsraum", "konvergent")});


            var drittePhaseKonvergentLabelRect = drittePhaseKonvergentGroup.append("rect")
                .attr("x", 1082)
                .attr("y", 165)
                .attr("width", 214)
                .attr("height", 60)
                .style("fill", "#70c46f")
                .on('click', function() {handleClick("implementierungsraum", "konvergent")});


            var drittePhaseKonvergentLabel = drittePhaseKonvergentGroup.append("text")
                .attr("dy", "0em")
                .attr("x", 1150)
                .attr("y", 190)
                .html("Lösung")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");

            var drittePhaseKonvergentLabel_2 = drittePhaseKonvergentGroup.append("text")
                .attr("dy", "1em")
                .attr("x", 1142)
                .attr("y", 193)
                .html("bewerten")
                .attr("font-family", "sans-serif")
                .attr("font-size", "22px")
                .attr("fill", "white");
            //Triangles
            //Erste Phase
            /**
            var triangle3 = svg.append('path')
                .attr('d', function(d) {
                    return 'M ' + 648 +' '+ 228 +
                        ' L' + 433 + ' ' + 527 +
                        ' L' + 648 + ' ' + 827 +
                        ' z';
                });
             **/
            var triangle1 = erstePhaseDivergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 215 + ' ' + 228 +
                    ' L' + 3 + ' ' + 527 +
                    ' L' + 215 + ' ' + 827 +
                    ' z';

                })
                .style("fill", "#8F56B2")
                .on('click', function() {handleClick("problemraum", "divergent")});

            var triangle2 = erstePhaseKonvergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 215 + ' ' + 228 +
                        ' L' + 431 + ' ' + 527 +
                        ' L' + 215 + ' ' + 827 +
                        ' z';

                })
                .style("fill", "#783D99")
                .on('click', function() {handleClick("problemraum", "konvergent")});

            var triangle3 = zweitePhaseDivergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 648 + ' ' + 228 +
                        ' L' + 433 + ' ' + 527 +
                        ' L' + 648 + ' ' + 827 +
                        ' z';

                })
                .style("fill", "#446F95")
                .on('click', function() {handleClick("lösungsraum", "divergent")});

            var triangle4 = zweitePhaseKonvergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 648 + ' ' + 228 +
                        ' L' + 864 + ' ' + 527 +
                        ' L' + 648 + ' ' + 827 +
                        ' z';

                })
                .style("fill", "#335E87")
                .on('click', function() {handleClick("lösungsraum", "konvergent")});

            var triangle5 = drittePhaseDivergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 1081 + ' ' + 228 +
                        ' L' + 866 + ' ' + 527 +
                        ' L' + 1081 + ' ' + 827 +
                        ' z';

                })
                .style("fill", "#3AA039")
                .on('click', function() {handleClick("implementierungsraum", "divergent")});

            var triangle6 = drittePhaseKonvergentGroup.append('path')
                .attr('d', function (d) {
                    return 'M ' + 1081 + ' ' + 228 +
                        ' L' + 1296 + ' ' + 527 +
                        ' L' + 1081 + ' ' + 827 +
                        ' z';

                })
                .style("fill", "#328131")
                .on('click', function() {handleClick("implementierungsraum", "konvergent")});





        }
    }



    function handleClick(room, phase) {
        //alert(room);


        $.ajax({
            url: base_path + '/dt/library/' + room + '/' + phase,
            cache: false,
            success: function (data) {
                methods_display(data);
            }
        });
    }

    function methods_display(data) {
        //alert(data);
        $('#block-dtmethodlibraryblock-2').html('<h2>Methoden in dieser Phase</h2>' + data);
    }
    Drupal.behaviors.dt_front = {
        attach: function (context, settings) {
            init();





        }
    }
}(jQuery, Drupal, drupalSettings));