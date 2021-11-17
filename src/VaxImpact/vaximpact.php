<!--- EXT IMPORTS --->
    <!--- CSS sheets --->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.css" integrity="sha512-XXtRBFtk/QfR8GEWwQPYjrQBHQwjidXg0wo8HJi9YOaFycWqd2uWkjJoAyx8Mb/+H8uhvmf70EAIxDnQxrwrvw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!--- Js scripts --->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.min.js" integrity="sha512-igVQ7hyQVijOUlfg3OmcTZLwYJIBXU63xL9RC12xBHNpmGJAktDnzl9Iw0J4yrSaQtDxTTVlwhY730vphoVqJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js" integrity="sha512-EnXkkBUGl2gBm/EIZEgwWpQNavsnBbeMtjklwAa7jLj60mJk932aqzXFmdPKCG6ge/i8iOCK0Uwl1Qp+S0zowg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>


<!--- Début HTML -->
<body>

<!--- VAXIMPACT IMPORTS --->
    <!--- CSS sheets --->
    <?php include(__DIR__ . '/vaximpactCss.php'); ?>

    <!--- Js scripts --->
    <?php include(__DIR__ . '/vaximpact_assetsJs.php'); ?>
    <?php include(__DIR__ . '/vaximpact_utilsJs.php'); ?>

    <!-- Les graphiques HTML fonctionnent sur la base de templates qui sont en include.-->
    <?php include(__DIR__ . '/templates.php'); ?>

    <br>
    
    <!--- Intro globale --->
    <p><b>Combien d'hospitalisations ont été évitées grâce au vaccin Covid19 ? Quelle est l'importance de la non vaccination du Covid19 dans les décès hospitaliers ?</b> Cet outil permet d'évaluer l'impact de la vaccination sur les hospitalisations et décès de la Covid19.
    Ces statistiques s'appuient sur les données issues des études de la DREES mises à jour chaque semaine (dernières données : du <span class="date-data-start">-/-/-</span> au <span class="date-data-end">-/-/-</span>). La méthodologie et les calculs sont décrits en bas de page.
    </p>

    <div class="alert alert-warning" role="alert">
    <i>29 octobre •</i><b> Changement de méthode</b><br>
        La DREES a modifié sa méthodologie d'appariement du statut vaccinal des personnes hospitalisées. Cela engendre un nombre de non vaccinés un peu moins important parmi les hospitalisés. Les données et graphiques ont été mis à jour retrospectivement, depuis le début. <a href="https://drees.solidarites-sante.gouv.fr/recherche?f%5B0%5D=content_type%3A506">Plus d'informations</a>
    </div>

    <br>
    <br>
    
    <div style="background: #f5f5f5; padding: 30px; border-radius: 20px;">

    <!--- Selecteur de région-->
    <?php include(__DIR__ . '/top_selectors.php'); ?>

    <!--- Précision composition groupes --->
    <!--<div class="alert alert-warning" role="alert">
        Le groupe "Vaccinés" comprend uniquement les patients ayant terminé leur schéma vaccinal et le groupe "Non Vaccinés" les patients n'ayant reçu aucune dose de vaccin.  (voir méthodologie en bas de page pour plus de détails).
    </div>-->

    <!--- Choix de la catégorie à afficher -->
    <!--- Il faut utiliser comme href le même nom que le field du fichier stats_by_week.json !!! --->
    <?php include(__DIR__ . '/nav_onglets.php'); ?>
    </div>

    <!--- Contenu des onglets qui sera généré en js --->
    <div class="tab-content" id="tabs" style="margin-top: 30px;"></div>

    <br>
    <br>

    <!--- Affichage du cadre méthodologie --->
    <div style="border:solid 2px rgba(0, 0, 0, 0.1); border-radius: 10px; padding: 30px; margin-top: 100px;">
        <h2>Méthodologie</h2>
        <p>Ces statistiques sont obtenues à partir des données publiées par la DREES. Elles seront mises à jour lors de la publication de nouvelles données.</p>
        <p><a href="https://github.com/CovidTrackerFr/data-utils/raw/main/vaximpact/data/doc/impact_vaccin_covid.pdf">Télécharger le document de méthodologie (PDF)</a></p>
    </div>

    <br>
    
    <!--- Liste des auteurs --->
    Auteurs : Sacha Guilhaumou, Elias Orphelin, Guillaume Rozier.<br>
    Merci pour leur aide : Catherine Hill, Dan Chaltiel. Merci pour leur relecture : Vittoria Colizza, Karine Lacombe, BioHospitalix, Le Doc.
    
    <br>

    <!--- Inclusion du menu du bas avec les outils -->
    <?php include(__DIR__ . '/menuBasPage.php'); ?>

</body>
<!--- Fin HTML -->

<!--- Début JS --->
<script>

// Permet d'afficher un onglet de la navbar lorsqu'on clique dessus.
$('#tabs_navbar a').click(function (e) {
     e.preventDefault()
$(this).tab('show')
})

// Fonction principale, télécharge les données pour la région sélectionnée
function download_data(region_code, first_load = false)
{
    var populate_region = false;
    if (region_code != "FR")
        {
            var populate_region = true;
        }
   
    fetch(data_url.format(region_code), {cache: 'no-cache'})
        .then(function(resp){
            return resp.json();
        })
        .then((data) => window.data_for_region = data)
        .then(function(){
            fetch(stats_url.format(region_code), {cache: 'no-cache'})
            .then(function(resp){ return resp.json()})
            .then((data) => window.stats_for_region = data)
            .then(function(){
                dates_from_json=[];
                for (date of Object.keys(stats_for_region["data_by_week"]))
                {
                    dates_from_json.push(date);
                }
                last_week_number = Math.max.apply(null, dates_from_json);

            })
            .then(function(){
            populate_figures(stats_for_region, data_for_region, last_week_number, populate_region, first_load)
            })
        });        
    
    }

    
// Par défaut, on initialise tous les graphiques pour la france entière.
download_data("FR", first_load = true);	

  
function populate_figures(stats, raw_data, last_week, populate_region = false, first_load = false){
    
    // Cas
    fillFigure(
        stats,
        raw_data,
        last_week,
        first_load,
        active_tab_by_default = true,
        tab_name = "cas",
        json_data_field = "nb_pcr0",
        graphique_intro="Ce graphique permet de visualiser le nombre de nouveaux cas de Covid-19 (symptomatiques ou non) diagnostiqués jour chez des individus de {0} en fonction du statut vaccinal des personnes.",
        graphique_link="https://raw.githubusercontent.com/CovidTrackerFr/covidtracker-data/master/images/charts/france/{0}pcr_plus_proportion_selon_statut_vaccinal{1}.jpeg",
        figures_data = [
            {
                figure_type : "reduction_risque", 
                field_name : "cas positif(s) pour 100 tests",
                icon_type : "virus",
                icon_color_vax : LIGHT_BLUE,
                icon_color_non_vax : "orange",
                title : "Réduction du risque de cas positifs",
                intro : "Imaginons deux groupes de 100 personnes, l'un comportant des individus vaccinés de {0} qui sont testés chaque jour, et l'autre uniquement des individus non vaccinés qui sont eux aussi testés chaque jour. S'il y a une personne testée positive au Covid-19 chaque jour chez les vaccinés, alors il y aura probablement {1} personnes testées positives chaque jour chez les non-vaccinés.",
                conclu : "Cela signifie qu'une personne non vaccinée a un risque multiplié par au moins {0} d'être infecté par le virus SARS-Cov2 par rapport à une personne vaccinée. À ce bénéfice individuel de la vaccination, il faut ajouter le bénéfice collectif : réduction des contaminations (protection individuelle et immunité collective) et donc réduction du risque individuel d'infection.",
            },
            { 
                figure_type : "fraction_attribuable",
                animate : true,
                field_name : "Infections évitables",
                icon_type : "virus",
                icon_color_vax: "black",
                icon_color_non_vax:"black",
                title : "Infections évitables",
                intro : "Cet indicateur permet d'estimer la proportion des infections qui auraient pu être évitées par la vaccination.",
                mini_conclu : "Cela signifie que sur 100 infections d'individus ayant {0} {1}, {2} cas auraient pu être évités par la vaccination.",
                conclu : "Cela signifie que sur les {0} infections observées le {1}, {2} infections auraient été directement évitables par la vaccination. D'autres infections auraient pu être indirectement évitées, la vaccination permettant de réduire les contaminations (protection individuelle et immunité collective).",
            }
        ]
    );

    
    
    // Hospitalisations
    fillFigure(
        stats,
        raw_data,
        last_week,
        first_load,
        active_tab_by_default = false,
        tab_name = "hospitalisation_conventionnelle",
        json_data_field = "hc_pcr",
        graphique_intro="Ce graphique permet de visualiser le nombre de nouvelles hospitalisations (hors réa.) pour suspiscion de Covid-19 ayant lieu chaque jour chez des individus de {0} en fonction du statut vaccinal des personnes.",
        graphique_link="https://raw.githubusercontent.com/CovidTrackerFr/covidtracker-data/master/images/charts/france/{0}hc_proportion_selon_statut_vaccinal{1}.jpeg",
        figures_data = [
            {
                figure_type : "reduction_risque", 
                field_name : "hospitalisation(s)",
                icon_type : "bed",
                icon_color_vax : LIGHT_BLUE,
                icon_color_non_vax : "orange",
                title : "Réduction du risque d'admission à l'hôpital",
                intro : "Imaginons deux groupes, l'un comportant des individus vaccinés de {0} et l'autre uniquement des individus non vaccinés. S'il y a une personne hospitalisée pour Covid-19 chaque jour dans le groupe des vaccinés, alors il y aura probablement {1} personnes hospitalisées chaque jour dans le groupe des non-vaccinés.",
                conclu : "Cela signifie qu'une personne non vaccinée a un risque multiplié par {0} d'être hospitalisée pour Covid-19 par rapport à une personne vaccinée. À ce bénéfice individuel de la vaccination, il faut ajouter le bénéfice collectif : réduction des contaminations (protection individuelle et immunité collective) et donc réduction du risque individuel d'infection.",
            },
            { 
                figure_type : "fraction_attribuable",
                animate : true,
                field_name : "Admissions à l'hopital",
                icon_type : "bed",
                icon_color_vax: "black",
                icon_color_non_vax:"black",
                title : "Admissions à l'hôpital évitables",
                intro : "Cet indicateur permet d'estimer la proportion d'hospitalisations (hors réa.) pour Covid-19 qui auraient pu être évitées par la vaccination.",
                mini_conclu : "Cela signifie que sur 100 hospitalisations pour Covid-19 ayant {0} {1}, {2} admissions auraient pu être évitées par la vaccination.",
                conclu : "Cela signifie que sur les {0} admissions hospitalières (hors réa.) observées le {1}, {2} hospitalisations auraient été directement évitables par la vaccination. D'autres admissions auraient pu être indirectement évités, la vaccination permettant de réduire les contaminations (protection individuelle et immunité collective).",
            }
        ]
    );

    // Réanimations
    fillFigure(
        stats,
        raw_data,
        last_week,
        first_load,
        active_tab_by_default = false,
        tab_name = "soins_critiques",
        json_data_field = "sc_pcr",
        graphique_intro="Ce graphique permet de visualiser le nombre de nouvelles admissions en soins critiques pour Covid-19 ayant lieu chaque jour chez des individus de {0} en fonction du statut vaccinal des personnes.",
        graphique_link="https://raw.githubusercontent.com/CovidTrackerFr/covidtracker-data/master/images/charts/france/{0}sc_proportion_selon_statut_vaccinal{1}.jpeg",
        figures_data = [
            {
                figure_type : "reduction_risque", 
                field_name : "admission(s) en réa.",
                icon_type : "bed",
                icon_color_vax : LIGHT_BLUE,
                icon_color_non_vax : "orange",
                title : "Réduction du risque d'admission en soins critiques",
                intro : "Imaginons deux groupes, l'un comportant des individus vaccinés de {0} et l'autre uniquement des individus non vaccinés. S'il y a une personne admise par jour en réanimation pour Covid-19 dans le groupe des vaccinés, alors il y aura probablement {1} personnes admises chaque jour dans le groupe des non-vaccinés.",
                conclu : "Cela signifie qu'une personne non vaccinée a un risque multiplié par {0} d'être admise en réanimation pour Covid-19 par rapport à une personne vaccinée. À ce bénéfice individuel de la vaccination, il faut ajouter le bénéfice collectif : réduction des contaminations (protection individuelle et immunité collective) et donc réduction du risque individuel d'infection.",
            },
            { 
                figure_type : "fraction_attribuable",
                animate : true,
                field_name : "Admissions en soins critiques",
                icon_type : "bed",
                icon_color_vax: "black",
                icon_color_non_vax:"black",
                title : "Admissions en soins critiques évitables",
                intro : "Cet indicateur permet d'estimer la proportion d'admissions en soins critiques pour Covid-19 qui auraient pu être évitées par la vaccination.",
                mini_conclu : "Cela signifie que sur 100 admissions en soins critiques pour Covid-19 ayant {0} {1}, {2} admissions auraient pu être évitées par la vaccination.",
                conclu : "Cela signifie que sur les {0} admissions en soins critiques observées le {1}, {2} admissions auraient été directement évitables par la vaccination. D'autres admissions auraient pu être indirectement évités, la vaccination permettant de réduire les contaminations (protection individuelle et immunité collective).",
            }
        ]
    );

    // if (populate_region == false)
    // {
    //     // Décès
    //     fillFigure(
    //         stats,
    //         raw_data,
    //         last_week,
    //         first_load,
    //         age="tous âges",
    //         active_tab_by_default = false,
    //         tab_name = "deces",
    //         json_data_field = "dc_pcr",
    //         graphique_intro="Ce graphique permet de visualiser le nombre de nouveaux décès hospitaliers chaque jour en fonction du statut vaccinal des personnes.",
    //         graphique_link="https://raw.githubusercontent.com/CovidTrackerFr/covidtracker-data/master/images/charts/france/dc_proportion_selon_statut_vaccinal.jpeg",
    //         figures_data = [
    //             {
    //                 figure_type : "reduction_risque", 
    //                 field_name : "décès",
    //                 icon_type : "body",
    //                 icon_color_vax : LIGHT_BLUE,
    //                 icon_color_non_vax : "orange",
    //                 title : "Réduction du risque de décès.",
    //                 intro : "Imaginons deux groupes, l'un comportant des individus vaccinés de {0} et l'autre uniquement des individus non vaccinés. S'il y a un décès du Covid-19 par jour dans le groupe des vaccinés, alors il y aura probablement {1} décès du Covid-19 chaque jour dans le groupe des non-vaccinés.",
    //                 conclu : "Cela signifie qu'une personne non vaccinée a un risque multiplié par {0} de décéder du Covid-19 par rapport à une personne vaccinée. À ce bénéfice individuel de la vaccination, il faut ajouter le bénéfice collectif : réduction des contaminations (protection individuelle et immunité collective) et donc réduction du risque individuel d'infection.",
    //             },
    //             { 
    //                 figure_type : "fraction_attribuable",
    //                 animate : true,
    //                 field_name : "Décès",
    //                 icon_type : "body",
    //                 icon_color_vax: "black",
    //                 icon_color_non_vax:"black",
    //                 title : "Décès du Covid-19 attribuables à la non vaccination.",
    //                 intro : "Cet indicateur permet d'estimer la proportion des décès du Covid-19 qui auraient pu être évités par la vaccination.",
    //                 mini_conclu : "Cela signifie que sur 100 décès Covid-19 ayant {0} {1}, {2} décès auraient pu être évitées par la vaccination.",
    //                 conclu : "Cela signifie que sur les {0} décès du COvid-19 observés le {1}, {2} décès auraient été directement évitables par la vaccination. D'autres décès auraient pu être indirectement évités, la vaccination permettant de réduire les contaminations (protection individuelle et immunité collective).",
    //             }
    //         ]
    //     );
    // }


    // On remplit toutes les dates "mis à jour le ..." d'un coup
    populate_dates(stats, last_week);
}


function fillFigure(stats, raw_data, last_week, active_tab_by_default, first_load, tab_name, json_data_field, graphique_intro, graphique_link, figures_data){

    // On créé un div au nom de l'onglet en cours
    if (document.querySelector("#"+tab_name))
    {
        var div_container = document.querySelector("#"+tab_name)
        div_container.innerHTML="";
    }
    
    else
    {
        var div_container = document.createElement("div");
        div_container.id=tab_name;
    }

    div_container.setAttribute("role", "tabpanel");
    div_container.classList.add("tab-pane");

    // S'il s'agit du 1er lancement et de l'onglet par défaut, on l'active
    if (first_load == true && active_tab_by_default==true)
    {
        div_container.classList.add("active");
    }

    if (graphique_intro!="" && graphique_link!="")
    {
        var graphe = document.querySelector('#template_graphique');
        graphe = document.importNode(graphe.content, true);

        var age = document.getElementById("select_age")
        var age_text = age.options[age.selectedIndex].text;

        var region = document.getElementById("select_region").options[document.getElementById("select_region").selectedIndex].text;

        if (region=="Toute la France"){
            folder = "";
                if(age.value!="all"){
                    var get_graph = "_"+age.value;
                }
                else{
                    var get_graph=""
                }
        }

        else{
            folder="regions_dashboards/"
            if (region == "Centre-Val de Loire"){
                region = "Centre"}
            var get_graph = "_"+region;
        }

       
        graphe.querySelector("#title").innerHTML = graphique_intro.format(age_text.toLowerCase());
        graphe.querySelector("#link").href = graphique_link.format(folder,get_graph);
        graphe.querySelector("#imageTab").src = graphique_link.format(folder,get_graph);

    
    }


    // Pour chaque onglet, on iterate les différentes figures
    for (figure of figures_data)
    {
        // On récupère la HTML template
        var template = document.querySelector('#template_'+figure.figure_type);
        template = document.importNode(template.content, true);
        var age = document.getElementById("select_age")
        var age_text = age.options[age.selectedIndex].text;
        age = age.value
        var FER_population = (parseFloat(stats["data_by_week"][last_week]["data"][age][tab_name]["FER_population"])).toFixed(0);

        if (figure.figure_type=="reduction_risque"){
            var raw_chiffre_non_vax = (parseFloat(stats["data_by_week"][last_week]["data"][age][tab_name]["risque_relatif"])).toFixed(0);
            var chiffre_vax = 1;
        }

        else if (figure.figure_type=="fraction_attribuable"){
            var raw_chiffre_non_vax = (parseFloat(stats["data_by_week"][last_week]["data"][age][tab_name]["FER_exposes"])).toFixed(0);
            var chiffre_vax = 100-raw_chiffre_non_vax;
        }


        // On récupère les icones pour dresser le graphique
        var vax_icons = get_icons(figure.icon_type, chiffre_vax, figure.icon_color_vax);
        var non_vax_icons = get_icons(figure.icon_type, raw_chiffre_non_vax, figure.icon_color_non_vax, figure.animate);

        var chiffre_vax = `1 ${figure.field_name}`;

        if (figure.figure_type=="reduction_risque"){
            var chiffre_non_vax = `${raw_chiffre_non_vax} ${figure.field_name}`;
        }
        else
        {
           var chiffre_non_vax=raw_chiffre_non_vax;
        }

        template.querySelector('#title').innerHTML = figure.title;
        template.querySelector('#description').innerHTML = figure.intro.format(age_text.toLowerCase(), raw_chiffre_non_vax);

        if (figure.figure_type=="reduction_risque"){

            template.querySelector('#chiffre_vax').innerHTML = chiffre_vax;
            template.querySelector('#chiffre_non_vax').innerHTML = chiffre_non_vax;
            template.querySelector('#figure_vax').innerHTML = vax_icons;
            template.querySelector('#figure_non_vax').innerHTML = non_vax_icons;
            template.querySelector('#conclusion').innerHTML = figure.conclu.format(raw_chiffre_non_vax);

            var slider = "slider_{0}".format(tab_name);
            template.querySelector('#slider').setAttribute("id",slider);
            template.querySelector('#'+slider).setAttribute("tab_name",tab_name);
            template.querySelector('#'+slider).setAttribute("field_name",figure.field_name);
            template.querySelector('#'+slider).setAttribute("icon_type",figure.icon_type);
            template.querySelector('#'+slider).setAttribute("non_vax_value",raw_chiffre_non_vax);
            
        }

        else if (figure.figure_type=="fraction_attribuable"){

            template.querySelector('#premiere_conclu').innerHTML = figure.mini_conclu.format(age_text.toLowerCase(), "chez les non vaccinés", chiffre_non_vax);
            template.querySelector('#premiere_conclu').setAttribute("tag",figure.mini_conclu);
            template.querySelector('#premiere_conclu').setAttribute("age",age_text.toLowerCase()); 
            template.querySelector('#conclusion').innerHTML = figure.conclu.format(raw_data["data_by_week"][last_week]["data"][age]["Vaccinés"][json_data_field], format_date_to_day_month(stats["data_by_week"][last_week]["week_end_date"]), parseFloat(raw_data["data_by_week"][last_week]["data"][age]["Vaccinés"][json_data_field]*FER_population/100).toFixed(0));
            template.querySelector('#figure_vax').innerHTML = vax_icons + non_vax_icons;
            template.querySelector('#group').innerHTML = figure.field_name + " chez les non vaccinés";
            template.querySelector('#group').setAttribute("tag",figure.field_name);
            template.querySelector('#chiffre_vax_evitables').innerHTML = chiffre_non_vax;
            template.querySelector('#chiffre_vax_evitables').setAttribute("non_vaccines",chiffre_non_vax);
            template.querySelector('#chiffre_vax_evitables').setAttribute("population",FER_population);

        }

    if (raw_chiffre_non_vax == -1){
        no_data(template, age, json_data_field, slider, raw_data, last_week);
    }

    if (graphe){div_container.appendChild(graphe);}
    div_container.appendChild(template);
    document.querySelector('#tabs').appendChild(div_container);

    }
}


// Récupère les données pour la bonne région
function selectRegion(selected){

    // var tab_deces = document.getElementById("tabs_navbar").querySelectorAll('a[href="#deces"]')[0]
    var selector_age = document.getElementById("select_age")
    selector_age.disabled = false

    // Dans le cas où on ne regarde pas la france entière, il faut désactiver les décès (pas de données)
    if (selected.value!="FR")
    {
        // tab_deces.classList.add("disable_link");
        // tab_deces.parentElement.classList.add("disabled");

        selector_age.querySelector('option[value="all"]').selected=true
        selector_age.disabled = true

        // Si l'utilisateur était sur un onglet décès, on le redirige sur le 1er onglet (ici cas)
        // if ($('#tabs_navbar .active')[0].id == "#deces")
        // {
        //     $('[href="#cas"]').tab('show');   
        // }
    }

    // Si l'utilisateur était sur une région et repasse sur la france, il faut réactiver l'onglet décès.
    // else
    // {
    //     tab_deces.classList.remove("disable_link");
    //     tab_deces.parentElement.classList.remove("disabled");
    // }

    // On récupère les données pour la région directement ! 
    download_data(selected.value);
}


// Récupère les données pour le bon âge
function selectAge(selected){

    var selector_region = document.getElementById("select_region")

    // On récupère les données pour la région directement ! 
    download_data(selector_region.value);
}

</script>
