var editing = 0;
var lastudpdate;
var editingnote=-1;

function updateidsearch(el) 
{
    editingnote = el.id.substr(5);
}

function updateidhead(el) 
{
    editingnote = el.id.substr(3); // 3 pour inp
}

function updateidtags(el)
{
    editingnote = el.id.substr(4);
}

function updateident(el)
{
    editingnote = el.id.substr(5); 
/*     var str = document.getElementById("entry"+editingnote).innerHTML;    // Me pose pbm pour la creation d'un link. ça le créé en début de note.
    var res = str.replace(/<br\s*[\/]?>/gi, "&nbsp;<br>");
    document.getElementById("entry"+editingnote).innerHTML = res; */
}

function updateiddoss(el)
{
    editingnote = el.id.substr(7);   // 7 pour "dossier"
}

function updateidsousdoss(el)
{
    editingnote = el.id.substr(8);    // 8 pour "sousdoss"
}

window.onbeforeunload = function(){
    if(editing!=0){
        return 'We are still attempting to save something...';
    }
};

function updatenote(){
    var headi = document.getElementById("inp"+editingnote).value;
    var ent = $("#entry"+editingnote).html();  // On récupère le contenu de la note et le transforme en html (les images sont converties en base64) pour l'enregistrer dans un fichier (fwrite dans updatenote.php)

    //console.log("RESULTAT :" + ent);
    
    var ent = ent.replace(/<br\s*[\/]?>/gi, "&nbsp;<br>");  // Remplacer les lignes vides par &nbsp; pour que si on format en code le saut de ligne est gardé    
    var entcontent = $("#entry"+editingnote).text(); // On récupère le texte de la note  pour l'enregistrer en base de donnée (dans updatenote.php)
    var doss = document.getElementById("dossier"+editingnote).value;
    var sousdoss = document.getElementById("sousdoss"+editingnote).value;	
    var tags = document.getElementById("tags"+editingnote).value;

    $.post( "updatenote.php", {pass: app_pass, id: editingnote, dossier: doss, sousdossier: sousdoss, tags: tags, heading: headi, entry: ent, entrycontent: entcontent, now: (new Date().getTime()/1000)-new Date().getTimezoneOffset()*60})
    .done(function(data) {
        if(data=='1')
        {
            editing = 0;
            $('#lastupdated'+editingnote).html('Last Saved Today');
        }
        else
        {
            editing = 0;
            $('#lastupdated'+editingnote).html(data);
        }
    });
    $('#newnotes').hide().show(0);
}

function newnote(dossier_selected, sousdossier){
    if (dossier_selected !== '') 
    {       
        if (sousdossier !== '') 
        {
            // Créer une note dans un sous-dossier
            $.post( "insertnew.php", {dossier: dossier_selected, sousdossier: sousdossier, pass: app_pass, now: (new Date().getTime()/1000)-new Date().getTimezoneOffset()*60})
            .done(function(data) {
                if(data=='1') 
                {
                    $(window).scrollTop(0);
                    location.reload(true);
                }
                else alert(data);
            });
        }
        else
        {
            // Créer une note dans un dossier
            $.post( "insertnew.php", {dossier: dossier_selected, pass: app_pass, now: (new Date().getTime()/1000)-new Date().getTimezoneOffset()*60})
            .done(function(data) {
                if(data=='1') 
                {
                    $(window).scrollTop(0);
                    location.reload(true);
                }
                else alert(data);
            });
        }       
    }
    else
    {
       alert("Merci de créer ou de sélectionner un dossier existant avant de créer une note !");
    } 
}          
                
function deletedossierJS(dossier_en_cours) {
    if (dossier_en_cours !== '') 
    {
        var dossier_to_remove = dossier_en_cours;
        if (dossier_to_remove != null){
            removedossier(dossier_to_remove);
        }
    }
    else
    {
       confirm("Merci de sélectionner d'abord dans la liste le dossier à supprimer !");
    } 
}     
                
function renamedossierJS(dossier_en_cours) {
    if (dossier_en_cours !== '') 
    {
        var dossier_a_renommer = dossier_en_cours;
        var nouveau_nom_dossier = window.prompt("Nouveau nom pour le dossier "+dossier_a_renommer+" ?");
        if (nouveau_nom_dossier != null){
            renamedossier(dossier_a_renommer, nouveau_nom_dossier);
        }
    }
    else
    {
       confirm("Merci de sélectionner d'abord dans la liste le dossier à renommer !");
    } 
}

function createNewdossierJS() {                    
    var new_dossier_to_create = window.prompt("Nom du dossier à créer ?");
    
    if (new_dossier_to_create != null){
        createnewdossier(new_dossier_to_create);
        newnote(new_dossier_to_create);                                       
    }           
}                 

function createnewdossier(new_dossier_to_create){
    if (new_dossier_to_create) 
    {
        if (/^[A-Za-z0-9À-ü \-_]+$/i.test(new_dossier_to_create)) 
        {
            $.post( "createdossier.php", {new_dossier: new_dossier_to_create, pass: app_pass})
            setTimeout(function(){ window.location.href = "index.php?doss="+new_dossier_to_create; }, 1000);
        }
        else
        {
            confirm("Le nom du dossier ne peut contenir que des espaces, des lettres, des chiffres, des tirets ou des underscores. Aucune modification effectuée.");
        } 
    }
    else
    {
       alert("Problème avec le nom du dossier fourni. Nom du dossier fourni vide ou égal à 0. Aucune création effectuée.");
    }
}

function renamedossier(dossier_actuel, new_dossier_name){
    if (dossier_actuel)
    {
        if (new_dossier_name)
        {
            if (/^[A-Za-z0-9À-ü \-_]+$/i.test(new_dossier_name)) {
               $.post( "renamedossier.php", {actuel_dossier: dossier_actuel, dossier_new_name: new_dossier_name, pass: app_pass})
               setTimeout(function(){ window.location.href = "index.php?doss="+new_dossier_name; }, 1000);
            }
            else
            {
               confirm("Le nom du dossier ne peut contenir que des espaces, lettres, des chiffres, des tirets ou des underscores. Aucune modification effectuée.");
            }                
        }
        else
        {
            alert("Problème avec le nom du dossier fourni. Vide ou égal à 0 ? Aucune modification effectuée.");
        } 
    }
    else
    {
       confirm("Problème avec le nom du dossier fourni. Vide ou égal à 0 ? Aucune modification effectuée.");
    } 
   
}

function removedossier(dossier_to_remove){
    if (dossier_to_remove) 
    {
        var r = confirm("Êtes-vous sûr de vouloir supprimer définitivement le dossier \""+dossier_to_remove+"\" et toutes ses notes (également celles de ce dossier qui sont dans la corbeille ?) Elles seront perdues à jamais !");
        if (r == true) {
            $.post( "removedossier.php", {del_dossier: dossier_to_remove, pass: app_pass})
            setTimeout(function(){ window.location.href = "index.php?dossier=tous"; }, 1000);
        }
    }
    else
    {
       alert("Problème avec le nom du dossier fourni. Nom du dossier fourni vide ou égal à 0. Aucune suppression effectuée.");
    }
        
}

function functionSendGetdossier() {
    var doss = document.getElementById("mydossierSelect").value;
    window.location.href = "index.php?dossier="+doss;
}   

function emptytrash(){
    var r = confirm("Êtes-vous sûr de vouloir supprimer définitivement toutes les notes de la corbeille ? Elles seront perdues à jamais !");
    if (r == true) {
        $.post( "emptytrash.php", {pass: app_pass})
        setTimeout(function(){ window.location.href = "trash.php"; }, 1000);
    }
}

function save(){
    alert('saved');
}

function checkedit(){
    if(editingnote==-1) return ;

    var curdate = new Date();
    var curtime = curdate.getTime();
    if(editing==1 && curtime-lastudpdate > 1000)
    {
        updatenote();
    }
}

function deletePermanent(iid){
    var r = confirm("Êtes-vous sûr de vouloir supprimer définitivement la note \""+document.getElementById("inp"+iid).value+"\" ? Elle sera perdue à jamais !");
    var dossier_name = document.getElementById("doss"+iid).value;	
    if (r == true) {
        $.post( "permanentDelete.php", {pass: app_pass, id:iid, dossier: dossier_name})
        .done(function(data) {
            if(data=='1') $('#note'+iid).hide();
            else alert(data);
        });
    }
}

function putBack(iid){
    $.post( "putback.php", {pass: app_pass, id:iid})
    .done(function(data) {
        if(data=='1') $('#note'+iid).hide();
        else alert(data);
    });
}

function deleteNote(iid){
        $.post( "deletenote.php", {pass: app_pass, id:iid})
        .done(function(data) {
            if(data=='1') $('#note'+iid).hide();
            else alert(data);
        });
		setTimeout(function(){ window.location.reload(); }, 1000);
}

function update(){
    if(editingnote=='search') return;
    editing = 1;
    var curdate = new Date();
    var curtime = curdate.getTime();
    lastudpdate = curtime;
    $('#lastupdated'+editingnote).html('<b><span style="color:#FF0000";>Enregistrement en cours...</span></b>');
}

$('body').on( 'keyup', '.name_doss', function (){
    update();
});

$('body').on( 'keyup', '.noteentry', function (){
    update();
});

$('body').on( 'click', '.popline-btn', function (){
    update();
});

$('body').on( 'keyup', 'input', function (){
    update();
});


$( document ).ready(function() {
    setInterval(function(){ checkedit(); }, 1000);
});
