<section id="main">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Actualité -->
                <section>
                    <header class="major">
                        <h2>Actualités</h2>
                    </header>
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">titre</th>
                            <th scope="col">contenu</th>
                            <th scope="col">date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (! empty($actu) && is_array($actu))
                            {
                                foreach ($actu as $actualite)
                                {   
                                echo("<tr>");
                                echo ("<td>".$actualite["titre_actualite_act"]."</td>");
                                echo ("<td>".$actualite["contenu_act"]."</td>");
                                echo("<td>".$actualite["date_actualite_act"]."</td>");
                                echo("</tr>"); 
                                }
                            }
                            else {
                                echo ("<h3>pas d'actualite</h3>");
                            }
                            
                        ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
</section>