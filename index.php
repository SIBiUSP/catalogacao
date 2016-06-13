<html>
    <head>
        <title>Framework de Catalogação</title>
        <?php include('inc/meta-header.php'); ?>
    </head>
    <body>
        <?php include('inc/barrausp.php'); ?>
        <div class="ui main container">
            <?php include('inc/header.php'); ?>
            <?php include('inc/navbar.php'); ?>
            <div id="main">
                <h3>Pesquisa Z39.50</h3>
                <form method="get" action="result.php">
                    <div class="ui form">
                        <div class="field">
                            <label>Fonte</label>
                            <select multiple="" class="ui dropdown" name="host[]">
                                <option value="">Selecione ao menos uma fonte</option>
                                <option value="dedalus.usp.br:9991/usp01" selected>USP - Dedalus</option>
                                <option value="marte.biblioteca.upm.es:2200" selected>Universidade de Madrid</option>  
                                <option value="lx2.loc.gov:210/LCDB" selected>Library of Congress</option>  
                                <option value="ilsz3950.nlm.nih.gov:7091/VOYAGER" selected>U.S. National Library of Medicine (NLM)</option>
                                <option value="sirsi.library.utoronto.ca:2200" selected>University of Toronto</option>                               
                                <!-- <option value="z3950.loc.gov:7090/voyager" selected>Library of Congress</option> -->
                                <!-- <option value="146.164.2.115:9991/UFR01">UFRJ - Minerva</option> -->
                                <option value="168.176.5.96:9991/SNB01" selected>UNAL - Universidade Nacional de Colombia</option>
                                <option value="athena.biblioteca.unesp.br:9992/uep01" selected>UNESP - Athena</option>
                                <option value="newton.lib.cam.ac.uk:7090/VOYAGER" selected>University of Cambridge</option>
                                <option value="zcat.libraries.psu.edu:2200" selected>Penn State University</option>
                                <option value="library.ox.ac.uk:210/aleph" selected>University of Oxford</option>
                                <option value="ringding.law.yale.edu:210/INNOPAC" selected>Yale Law School</option>
                                <!-- <option value="aleph50018.pucsp.br:9992/psp01">PUCSP</option> -->
                             </select>
                        </div>
                        <!--
                        <label>RPN Query:</label>
                        <input type="text" size="30" name="query" />
                        -->
                        <label>ISBN</label>
                        <input type="text" size="13" name="isbn" />
                        <br/><br/>
                        <input class="ui primary button" type="submit" name="action" value="Pesquisa Z39.50" />
                    </div>
                </form>
                <h3>Pesquisa DOI - CrossRef</h3>
                <form method="get" action="result.php">
                    <div class="ui form">
                        <div class="field">
                        <label>DOI (Sem http://dx.doi.org/)</label>
                        <input type="text" size="50" name="doi" />
                        <br/><br/>
                        <input class="ui primary button" type="submit" name="action" value="Pesquisa DOI" />
                    </div>
                </form>
                
            </div>            
        </div>
        <script>
            $('.ui.dropdown')
              .dropdown()
            ;
        </script>
        <?php include('inc/footer.php'); ?>
    </body>
</html>