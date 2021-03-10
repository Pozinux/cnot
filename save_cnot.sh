#!/bin/bash
 
bdd_host="localhost"
bdd_user="userdb"
bdd_password="20805547Tt!1"
bdd_name="cnot"
date_heure=$(date +%Y%m%d%H%M%S)
backup_folder="/root/save_cnot/"
app_name="cnot"
app_folder="cnot"
sql_backup_file="backup_bdd_${bdd_name}_${date_heure}.sql"
app_backup_file="backup_app_${app_name}_${date_heure}.targz"

# Sauvegarde de l'appli 
echo "Starting backup of the app files..."
cd /var/www/html/
tar --exclude='cnot/.git' -czvf ${backup_folder}/${app_backup_file} ${app_folder}
echo "Backup appli done! Backup file is ${backup_folder}${app_backup_file}"
 
# Sauvegarde de la bdd
cd ${backup_folder}
echo "Starting backup of the BDD..."
mysqldump --host=${bdd_host} --user=${bdd_user} --password=${bdd_password} ${bdd_name} > ${sql_backup_file}
echo "Backup BDD done! Backup file is ${backup_folder}${sql_backup_file}"
