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
                <h3>Escolha a fonte</h3>
                <form method="get" action="result.php">
                    <div class="ui form">
                        <div class="field">
                            <label>Fonte</label>
                            <select multiple="" class="ui dropdown" name="host[]">
                                <option value="">Selecione ao menos uma fonte</option>
                                <option value="dedalus.usp.br:9991/usp01" selected>USP - Dedalus</option>
                                <option value="z3950.loc.gov:7090/voyager">Library of Congress</option>
                                <!-- <option value="146.164.2.115:9991/UFR01">UFRJ - Minerva</option> -->
                                <option value="168.176.5.96:9991/SNB01">UNAL - Universidade Nacional de Colombia</option>
                                <option value="athena.biblioteca.unesp.br:9992/uep01">UNESP - Athena</option>
                                <option value="library.ox.ac.uk:210/aleph">University of Oxford</option>
                                <!-- <option value="aleph50018.pucsp.br:9992/psp01">PUCSP</option> -->
                            </select>
                        </div>
                        <label>RPN Query:</label>
                        <input type="text" size="30" name="query" />
                        <label>ISBN</label>
                        <input type="text" size="13" name="isbn" />
                        <br/><br/>
                        <input class="ui primary button" type="submit" name="action" value="Pesquisa Z39.50" />
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