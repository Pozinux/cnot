var editing = 0;
var lastudpdate;
var editingnote=-1;

function updateidsearch(el) 
{
    editingnote = el.id.substr(5);
}


function updateidhead(el) 
{
    editingnote = el.id.substr(3);
}

function updateidtags(el)
{
    editingnote = el.id.substr(4);
}

function updateident(el)
{
    editingnote = el.id.substr(5); 
}

function updateiddoss(el)
{
    editingnote = el.id.substr(4); 
}

window.onbeforeunload = function(){
    if(editing!=0){
        return 'We are still attempting to save something...';
    }
};

function updatenote(){
    var headi = document.getElementById("inp"+editingnote).value;
    var ent = $("#entry"+editingnote).html();
    var entcontent = $("#entry"+editingnote).text();
    var doss = document.getElementById("doss"+editingnote).value;	
    var tags = document.getElementById("tags"+editingnote).value;	

    $.post( "updatenote.php", {pass: app_pass, id: editingnote, dossier: doss, tags: tags, heading: headi, entry: ent, entrycontent: entcontent, now: (new Date().getTime()/1000)-new Date().getTimezoneOffset()*60})
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

function newnote(dossier_selected){
    if (dossier_selected !== '') 
    {
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
    else
    {
       alert("Merci de créer ou de sélectionner un dossier existant avant de créer une note !");
    } 
}

function createnewdossier(new_dossier_to_create){
    if (new_dossier_to_create) 
    {
        $.post( "createdossier.php", {new_dossier: new_dossier_to_create, pass: app_pass})
        setTimeout(function(){ window.location.href = "index.php?doss="+new_dossier_to_create; }, 1000);
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
            if (/\s/.test(new_dossier_name)) {
                confirm("Problème avec le nom du nouveau dossier fourni. Nom du nouveau dossier fourni contient un espace. Aucune modification effectuée.");
            }
            else
            {
               $.post( "renamedossier.php", {actuel_dossier: dossier_actuel, dossier_new_name: new_dossier_name, pass: app_pass})
               setTimeout(function(){ window.location.href = "index.php?doss="+new_dossier_name; }, 1000);
            }                
        }
        else
        {
            alert("Problème avec le nom des dossiers fournis. Vide ou égal à 0 ? Aucune modification effectuée.");
        } 
    }
    else
    {
       confirm("Problème avec le nom des dossiers fournis. Vide ou égal à 0 ? Aucune modification effectuée.");
    } 
   
}

function removedossier(dossier_to_remove){
    if (dossier_to_remove) 
    {
        var r = confirm("Êtes-vous sûr de vouloir supprimer définitivement le dossier \""+dossier_to_remove+"\" et toutes ses notes (également celles de ce dossier qui sont dans la corbeille ? Elles seront perdues à jamais !");
        if (r == true) {
            $.post( "removedossier.php", {del_dossier: dossier_to_remove, pass: app_pass})
            setTimeout(function(){ window.location.href = "index.php?doss=tous"; }, 1000);
        }
    }
    else
    {
       alert("Problème avec le nom du dossier fourni. Nom du dossier fourni vide ou égal à 0. Aucune suppression effectuée.");
    }
        
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
