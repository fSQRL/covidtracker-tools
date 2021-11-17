<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js"
        integrity="sha512-9hzM/Gfa9KP1hSBlq3/zyNF/dfbcjAYwUTBWYX+xi8fzfAPHL3ILwS1ci0CTVeuXTGkRAWgRMZZwtSNV7P+nfw=="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-deferred@1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@1"></script>


<?php include(dirname(__DIR__) . '/message_erreur.php') ?>

<?php include(__DIR__ . '/vaccintrackerCss.php'); ?>

<!-- wp:html -->

<p>Quelle proportion des Français a été vaccinée ? Combien faut-il encore vacciner de personnes avant d'atteindre
    l'immunité collective ? Quels sont les différents types de vaccins proposés ? Ce tracker permet de suivre la proportion de Français déjà vaccinés contre la Covid19. VaccinTracker est une initiative citoyenne indépendante et
    non officielle.
</p>


<div id="news"></div>

<div class="alert alert-info clearFix" style="font-size: 18px;">
    <div class="row">
        <div class="col-md-8">
            CovidTracker est gratuit.<br>
        </div>
        <div class="col-md-4 text-right">
            <button class="btn-shadow">
                <a href="https://lydia-app.com/collect/covidtracker/fr" target="_blank" rel="noreferrer noopener">🍩
                    Offrez-moi un donut</a>
            </button> &nbsp;&nbsp;
        </div>
    </div>
</div>


<!-- /wp:html -->

<?php include(__DIR__.'/resume.php'); ?>
<!--<p style="font-size: 80%; padding-top: 17px;"><i><b>* 10 avril 2021 - Information sur les données de livraisons de vaccins.</b> Les données de livraison de vaccins fournies par le Ministère de la Santé considèrent que toutes les doses sont livrées le dimanche de chaque semaine. Or, les livraisons sont en réalité étalées sur toute la semaine. Cela entraine des graphiques où, sur certains jours, les courbes des injections dépassent les courbes des livraisons. Pour cette raison, à partir d'aujourd'hui nous considérerons que les livraisons sont effectuées le jeudi de chaque semaine, et non plus le dimanche.</i>  </p>-->


<?php include(__DIR__.'/proportionVaccines.php'); ?>
<?php include(__DIR__.'/evolution.php'); ?>
<?php include(__DIR__.'/repartition.php'); ?>
<?php include(__DIR__. '/vaccinationsParAge.php') ?>
<?php include(__DIR__.'/typesVaccins.php'); ?>

<?php include(__DIR__ . '/vaccinationEHPAD.php') ?>
<?php include(__DIR__ . '/dansLeMonde.php') ?>
<br>
<br>
<?php include(__DIR__ . '/vaccintrackerJs.php'); ?>
<?php include(__DIR__ . '/menuBasPage.php'); ?>
<!-- /wp:html -->
<br>
Contributeurs de VaccinTracker : Aymerik Diebold, Florent Jaby, <a href="https://twitter.com/guillaumerozier">Guillaume
    Rozier</a>, Michael Souvy.
<br>
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link"
                                                 href="https://lydia-app.com/collect/covidtracker/fr" target="_blank"
                                                 rel="noreferrer noopener">☕️ Offrez-moi un café</a></div>

<!-- wp:spacer -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
