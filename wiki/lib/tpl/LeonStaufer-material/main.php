<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="<?php echo $conf['lang'] ?>"
      lang="<?php echo $conf['lang'] ?>"
      dir="<?php echo $lang['direction'] ?>">

<head>
    <meta charset="UTF-8"/>
    <link rel="manifest" href="/manifest.json">
    <title>
        <?php echo ucfirst(tpl_pagetitle(null, true)) ?> |
        <?php echo hsc($conf['title']) ?>
    </title>


    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <?php tpl_metaheaders() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <!-- Search -->
    <meta name="robots" content="noimageindex"/>
    <meta name="googlebot" content="noimageindex"/>

    <!-- Styling -->
    <meta name="theme-color" content="<?php echo parse_ini_file("style.ini")["__primary__"] ?>">

    <meta name="apple-mobile-web-app-status-bar-style" content="#<?php echo parse_ini_file("style.ini")["__primary__"] ?>">
    <?php echo tpl_favicon(array('favicon')) ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</head>

<body>
<div class="mdl-layout mdl-js-layout 
             <?php echo tpl_classes(); ?> mdl-layout--fixed-header" id="dokuwiki__top">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <span class="mdl-layout-title content-title" style="cursor: pointer;"onclick="document.location.href='doku.php?id=trapta'">TRAPTA&nbsp;&nbsp;&nbsp;</span>
            <div class="mdl-tabs__tab-bar traps-bar">
                <a href="http://score.trapta.eu" class="mdl-tabs__tab">Résultats en direct</a>
                <a href="doku.php?id=applications" class="mdl-tabs__tab <?php if (tpl_pagetitle(null, true)=="applications") echo "traps-menu-active"?>">Applications</a>
                <a href="doku.php?id=documentation" class="mdl-tabs__tab <?php if (tpl_pagetitle(null, true)=="documentation" || preg_match("/doc:/",tpl_pagetitle(null, true))) echo "traps-menu-active"?>">Documentation</a>
                

            </div>

            <div class="mdl-layout-spacer"></div>
            <form action="<?php echo DOKU_BASE . "doku.php"; ?>" accept-charset="utf-8" class="search" id="dw__search"
                  role="search">
                <input type="hidden" name="do" value="search">
                <div class="content-search">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input class="mdl-textfield__input edit" id="qsearch__in" accesskey="f" name="id"
                               title="[F]" autocomplete="off">
                        <label class="mdl-textfield__label" for="qsearch__in">Search</label>
                    </div>
                </div>
                <div id="qsearch__out" class="ajax_qsearch JSpopup content-search__popup" style="display: none;"></div>
            </form>
            <?php
            if ($INFO['userinfo'] == null) {
                $type = 'login';
            } else $type = 'profile';
          
            if ($INFO['userinfo'] != null){
                tpl_action('login', true, false, false, '', '', "
                <button class=\"mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect mdl-button--colored\">
                  <i class=\"material-icons\">exit_to_app</i>
                </button>");
            }?>
            <?php
            tpl_action('admin', true, false, false, '', '', "
                        <button class=\"mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect mdl-button--colored\">
                        <i class=\"material-icons\">settings</i>
                        </button>") ?>
        </div>
        
    </header>
    <div class="mdl-layout__drawer">
        <section class="drawer-top" >
            <span class="mdl-logo"><?php tpl_link(wl(), '<img src="http://www.trapta.eu/res/logo-menu.png" alt="' . $conf['title'] . '" />', 'id="dokuwiki__top" accesskey="h" title="[H]"'); ?></span>
            <br>
            <?php if ($conf['tagline']): ?>
                <p class="drawer-tagline">
                    <?php echo $conf['tagline'] ?>
                </p>
            <?php endif ?>
        </section>
        <nav class="mdl-navigation mdl-layout-spacer">
            <?php
            if($conf["sidebar"] != "") include("sidebar.php");?>
            <div class="mdl-layout-spacer" style="max-height: 20px"></div>

        </nav>
        <?php /*TODO: re-add registration: tpl_action('register');*/ ?>
    </div>
    <main class="mdl-layout__content">
        <?php if (tpl_pagetitle(null, true)=="trapta") tpl_includeFile('jumbotron.html') ?>
        <div class="page-content">
            <article class="content-card">
                <div class="content-actions" <?php if (!($ACT == "search" || $ACT == "edit" || $ACT == "show" || $ACT == "revisions") || $INFO['writable'] == false) echo "hidden=\"hidden\""?>>
                    <div class="content-actions__container">
                        <div class="content-actions__action">
                            <?php
                            if ($ACT == 'show' || $ACT == 'search') {
                                if (!$INFO['exists']) {
                                    $txt = "add";
                                } else $txt = "edit";
                            } else $txt = "arrow_back";
                            tpl_action('edit', true, false, false, '', '', "
                                <button class=\"mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored content-actions__action-button\">
                                    <i class=\"material-icons\" id=\"tpl_editBtn\">" . $txt . "</i>
                                </button>") ?>
                        </div>
                        <div class="content-actions__action" <?php if ($ACT == "search" ) echo "hidden=\"hidden\""; ?>>
                            <?php tpl_action('revisions', true, false, false, '', '', "
                                <button class=\"mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored content-actions__action-button\">
                                    <i class=\"material-icons\">history</i>
                                </button>") ?>
                        </div>
                    </div>
                </div>
                
                <div class="content-card__text">
                    <?php
                    /*  Do you see the heading twice because you have 'useheading' enabled?
                        You can use one of these two plugins to elegantly hide the redudant second title
                            -https://www.dokuwiki.org/plugin:pagetitle
                            -https://www.dokuwiki.org/plugin:hiddenheader
                    */
                    tpl_content(); ?>
                </div>
            </article>
        </div>
        <div class="mdl-layout-spacer"></div>

    </main>
</div>
<?php tpl_indexerWebBug(); ?>
</body>
</html>
