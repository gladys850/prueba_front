<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TmpCreateFunctionUpdateAffiliateIdPersonSenasir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE FUNCTION public.tmp_update_affiliate_id_senasir(db_name_intext text)
         RETURNS character varying
         AS $$
                              declare
                              type_state varchar;

                              affiliate_id_result integer;
                              criterion_one integer:= 1;
                              criterion_two integer:= 2;
                              criterion_three integer:= 3;
                              criterion_four integer:= 4;
                              criterion_five integer:= 5;

                             ------------------------------
                              cant varchar ;
                              affiliate_id_reg integer;
                              user_id_reg integer := 1;
                              pension_entity_id_reg integer :=5;
                              affiliate_state_id_reg_fall  integer :=4;
                              affiliate_state_id_reg_jub  integer :=5;
                              count_loan integer := 0;
                              count_economic_complemet integer := 0;
                              count_retirement_fund integer := 0;
                              count_quota_aid_mortuary integer := 0;
                              status_process integer:=0;

                             ---------------------------------
                           count_update_one_criterion integer :=0;
                           count_update_two_criterion integer :=0;
                           count_update_three_criterion integer :=0;
                           count_update_four_criterion integer :=0;
                           count_update_five_criterion integer :=0;

                             -- Declaración EXPLICITA del cursor
                               cur_payroll CURSOR for (select * from dblink(db_name_intext,'SELECT id,id_person_senasir,matricula_tit,carnet_tit,num_com_tit,concat_carnet_num_com_tit,
                               p_nombre_tit,s_nombre_tit,paterno_tit,materno_tit,ap_casada_tit,fecha_nacimiento_tit,
                           genero_tit,fec_fail_tit,matricula_dh,carnet_dh,num_com_dh,concat_carnet_num_com_dh,
                           p_nombre_dh,s_nombre_dh,paterno_dh,materno_dh,ap_casada_dh,fecha_nacimiento_dh,
                           genero_dh,fec_fail_dh,clase_renta_dh,state,observacion FROM copy_person_senasirs
                               where state = ''unrealized''')
                              as  copy_person_senasirs(id integer,id_person_senasir integer ,matricula_tit character varying(250),carnet_tit character varying(250),num_com_tit character varying(250),concat_carnet_num_com_tit character varying(250),
                           p_nombre_tit character varying(250),s_nombre_tit character varying(250),paterno_tit character varying(250),materno_tit character varying(250),ap_casada_tit character varying(250),fecha_nacimiento_tit date,
                           genero_tit character varying(250),fec_fail_tit date,matricula_dh character varying(250),carnet_dh character varying(250),num_com_dh character varying(250),concat_carnet_num_com_dh character varying(250),
                           p_nombre_dh character varying(250),s_nombre_dh character varying(250),paterno_dh character varying(250),materno_dh character varying(250),ap_casada_dh character varying(250),fecha_nacimiento_dh date,
                           genero_dh character varying(250),fec_fail_dh date,clase_renta_dh character varying(250),state character varying(250),observacion character varying));

                           begin
                                       --************************************************************
                                       --*Funcion actualizacion de ids, de acuerdo a 5 criterios
                                       --************************************************************
                                       -- Procesa el cursor
                                    FOR record_row IN cur_payroll loop
                                    ----Condicion que pregunta por el primer criterio

                                   if identified_affiliate(criterion_one,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit) > 0 THEN
                                         affiliate_id_result := identified_affiliate(criterion_one,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit);

                                          type_state:='1-CI-MAT-PN-AP-AM-FN';
                                          count_update_one_criterion:= count_update_one_criterion + 1;
                                           UPDATE public.affiliates
                                             SET id_person_senasir = record_row.id_person_senasir,
                                             updated_at = (select current_timestamp)
                                           WHERE affiliates.id = affiliate_id_result and affiliates.id_person_senasir is null;

                                    elsif identified_affiliate(criterion_two,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit) > 0 THEN

                                         affiliate_id_result := identified_affiliate(criterion_two,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit);
                                          type_state:='2-CI-PN-AP-AM-FN';
                                          count_update_two_criterion:= count_update_two_criterion + 1;
                                           UPDATE public.affiliates
                                             SET id_person_senasir = record_row.id_person_senasir,
                                             updated_at = (select current_timestamp)
                                           WHERE affiliates.id = affiliate_id_result and affiliates.id_person_senasir is null;

                                    elsif identified_affiliate(criterion_three,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit) > 0 THEN
                                    affiliate_id_result := identified_affiliate(criterion_three,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit);

                                          type_state:='3-CI-MAT-PN-AP-AM';
                                          count_update_three_criterion:= count_update_three_criterion + 1;
                                           UPDATE public.affiliates
                                             SET id_person_senasir = record_row.id_person_senasir,
                                             updated_at = (select current_timestamp)
                                           WHERE affiliates.id = affiliate_id_result and affiliates.id_person_senasir is null;

                                    elsif identified_affiliate(criterion_four,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit) > 0 THEN
                                    affiliate_id_result := identified_affiliate(criterion_four,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit);

                                          type_state:='4-MAT-PN-AP-AM-FN';
                                          count_update_four_criterion:= count_update_four_criterion + 1;
                                           UPDATE public.affiliates
                                             SET id_person_senasir = record_row.id_person_senasir,
                                             updated_at = (select current_timestamp)
                                           WHERE affiliates.id = affiliate_id_result and affiliates.id_person_senasir is null;
                                     elsif identified_affiliate(criterion_five,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit) > 0 THEN
                                     affiliate_id_result := identified_affiliate(criterion_five,record_row.concat_carnet_num_com_tit,record_row.matricula_tit,record_row.p_nombre_tit,record_row.paterno_tit,record_row.materno_tit,record_row.fecha_nacimiento_tit);

                                          type_state:='5-CI-PN-AP-AM';
                                          count_update_five_criterion:= count_update_five_criterion + 1;
                                           UPDATE public.affiliates
                                             SET id_person_senasir = record_row.id_person_senasir,
                                             updated_at = (select current_timestamp)
                                           WHERE affiliates.id = affiliate_id_result and affiliates.id_person_senasir is null;

                                      END IF;

                                --------------------------------------------------------------------------------------------------
                                      if exists (select id from affiliates where id_person_senasir =record_row.id_person_senasir) then
                                         affiliate_id_reg:= (select id from affiliates where id_person_senasir =record_row.id_person_senasir);
                                        ---- identificacion de tramites
                                         count_loan:= quantity_procedure_affiliate(affiliate_id_reg, 'l');
                                         count_economic_complemet:= quantity_procedure_affiliate(affiliate_id_reg, 'ec');
                                         count_retirement_fund:= quantity_procedure_affiliate(affiliate_id_reg,'rf');
                                         count_quota_aid_mortuary := quantity_procedure_affiliate(affiliate_id_reg,'qam');

                                         cant:=  (select dblink_exec(db_name_intext, 'UPDATE copy_person_senasirs SET state=''accomplished'',observacion='''||type_state||''', quantity_l='||count_loan||',quantity_ec='||count_economic_complemet||',quantity_rf='||count_retirement_fund||',quantity_qam='||count_quota_aid_mortuary||' WHERE copy_person_senasirs.id= '||record_row.id||''));                                  
                                         ---------------------
                                      END IF;
                                  END LOOP;
                       return count_update_one_criterion||','||count_update_two_criterion||','||count_update_three_criterion||','|| count_update_four_criterion||','||count_update_five_criterion;
                       end;
                 $$ LANGUAGE 'plpgsql'
         ;");
         
        DB::statement("CREATE OR REPLACE FUNCTION public.tmp_update_affiliate_id_senasir_registration_and_identity_card(db_name_intext text)
         RETURNS character varying
         AS $$
               declare
               type_state varchar;
               quantity integer := 1;
              ------------------------------
               cant varchar ;
               affiliate_id_reg integer;
              ------------------------------
               count_loan integer := 0;
               count_economic_complemet integer := 0;
               count_retirement_fund integer := 0;
               count_quota_aid_mortuary integer := 0;
              ---------------------------------
            count_update_six_criterion integer :=0;
            count_update_seven_criterion integer :=0;
              -- Declaración EXPLICITA del cursor
                cur_payroll CURSOR for (select * from dblink(db_name_intext,'SELECT id,id_person_senasir,matricula_tit,carnet_tit,num_com_tit,concat_carnet_num_com_tit,
                p_nombre_tit,s_nombre_tit,paterno_tit,materno_tit,ap_casada_tit,fecha_nacimiento_tit,
            genero_tit,fec_fail_tit,matricula_dh,carnet_dh,num_com_dh,concat_carnet_num_com_dh,
            p_nombre_dh,s_nombre_dh,paterno_dh,materno_dh,ap_casada_dh,fecha_nacimiento_dh,
            genero_dh,fec_fail_dh,clase_renta_dh,state,observacion FROM copy_person_senasirs
                where state = ''unrealized''')
                as  copy_person_senasirs(id integer,id_person_senasir integer ,matricula_tit character varying(250),carnet_tit character varying(250),num_com_tit character varying(250),concat_carnet_num_com_tit character varying(250),
                  p_nombre_tit character varying(250),s_nombre_tit character varying(250),paterno_tit character varying(250),materno_tit character varying(250),ap_casada_tit character varying(250),fecha_nacimiento_tit date,
                  genero_tit character varying(250),fec_fail_tit date,matricula_dh character varying(250),carnet_dh character varying(250),num_com_dh character varying(250),concat_carnet_num_com_dh character varying(250),
                  p_nombre_dh character varying(250),s_nombre_dh character varying(250),paterno_dh character varying(250),materno_dh character varying(250),ap_casada_dh character varying(250),fecha_nacimiento_dh date,
                  genero_dh character varying(250),fec_fail_dh date,clase_renta_dh character varying(250),state character varying(250),observacion character varying));
            begin
                        --************************************************************
                        --*Funcion actualizacion de ids, de acuerdo a 6 y 7 criterios matricula y carnet
                        --************************************************************
                        -- Procesa el cursor
                FOR record_row IN cur_payroll loop
                     ----Condicion que pregunta por el prim
                    if (quantity_regitration(record_row.matricula_tit) = 1) then
                      type_state:='6-MAT-REV-MANUAL';
                      count_update_six_criterion:= count_update_six_criterion + 1;
                         UPDATE public.affiliates
                         SET id_person_senasir = record_row.id_person_senasir,
                         updated_at = (select current_timestamp)
                         WHERE affiliates.registration = record_row.matricula_tit and affiliates.id_person_senasir is null;
                       ELSIF quantity_identity_card(record_row.concat_carnet_num_com_tit) = quantity and record_row.concat_carnet_num_com_tit != '0' THEN
                       type_state:='7-CI-REV-MANUAL';
                       count_update_seven_criterion:= count_update_seven_criterion + 1;
                            UPDATE public.affiliates
                           SET id_person_senasir = record_row.id_person_senasir,
                            updated_at = (select current_timestamp)
                            WHERE affiliates.identity_card = record_row.concat_carnet_num_com_tit and affiliates.id_person_senasir is null;
                    END IF;

                 --------------------------------------------------------------------------------------------------
                       if exists (select id from affiliates where id_person_senasir = record_row.id_person_senasir) then
                          affiliate_id_reg:= (select id from affiliates where id_person_senasir =record_row.id_person_senasir);
                         ---- identificacion de tramites
                          count_loan:= quantity_procedure_affiliate(affiliate_id_reg, 'l');
                          count_economic_complemet:= quantity_procedure_affiliate(affiliate_id_reg, 'ec');
                          count_retirement_fund:= quantity_procedure_affiliate(affiliate_id_reg,'rf');
                          count_quota_aid_mortuary := quantity_procedure_affiliate(affiliate_id_reg,'qam');
                          cant:=  (select dblink_exec(db_name_intext, 'UPDATE copy_person_senasirs SET state=''accomplished'',observacion='''||type_state||''', quantity_l='||count_loan||',quantity_ec='||count_economic_complemet||',quantity_rf='||count_retirement_fund||',quantity_qam='||count_quota_aid_mortuary||' WHERE copy_person_senasirs.id= '||record_row.id||''));                                  
                          ---------------------
                       END IF;
                END LOOP;
                return count_update_six_criterion||','||count_update_seven_criterion;
             end;
              $$ LANGUAGE plpgsql;");

        DB::statement("CREATE OR REPLACE FUNCTION public.tmp_update_affiliate_data(db_name_intext text)
RETURNS character varying
AS $$
      declare
      ----------

      affiliate_id_reg integer;
    ------------------------------------
     pension_entity_id_reg integer :=5;
     affiliate_state_id_reg_fall  integer :=4;
     affiliate_state_id_reg_jub  integer :=5;
     ------------------------------
     -- Declaración EXPLICITA del cursor
       cur_payroll CURSOR for (select * from dblink(db_name_intext,'SELECT id,id_person_senasir,matricula_tit,carnet_tit,num_com_tit,concat_carnet_num_com_tit,
       p_nombre_tit,s_nombre_tit,paterno_tit,materno_tit,ap_casada_tit,fecha_nacimiento_tit,
   genero_tit,fec_fail_tit,matricula_dh,carnet_dh,num_com_dh,concat_carnet_num_com_dh,
   p_nombre_dh,s_nombre_dh,paterno_dh,materno_dh,ap_casada_dh,fecha_nacimiento_dh,
   genero_dh,fec_fail_dh,clase_renta_dh,state,observacion FROM copy_person_senasirs
       where state = ''accomplished''')
       as  copy_person_senasirs(id integer,id_person_senasir integer ,matricula_tit character varying(250),carnet_tit character varying(250),num_com_tit character varying(250),concat_carnet_num_com_tit character varying(250),
         p_nombre_tit character varying(250),s_nombre_tit character varying(250),paterno_tit character varying(250),materno_tit character varying(250),ap_casada_tit character varying(250),fecha_nacimiento_tit date,
         genero_tit character varying(250),fec_fail_tit date,matricula_dh character varying(250),carnet_dh character varying(250),num_com_dh character varying(250),concat_carnet_num_com_dh character varying(250),
         p_nombre_dh character varying(250),s_nombre_dh character varying(250),paterno_dh character varying(250),materno_dh character varying(250),ap_casada_dh character varying(250),fecha_nacimiento_dh date,
         genero_dh character varying(250),fec_fail_dh date,clase_renta_dh character varying(250),state character varying(250),observacion character varying));
   begin
               --************************************************************
               --*Funcion actualizacion de Datos del afiliado
               --************************************************************
               -- Procesa el cursor
       FOR record_row IN cur_payroll loop
        --------------------------------------------------------------------------------------------------
              if exists (select id from affiliates where id_person_senasir = record_row.id_person_senasir) then
                 affiliate_id_reg:= (select id from affiliates where id_person_senasir =record_row.id_person_senasir);
                     UPDATE public.affiliates
                        set registration = record_row.matricula_tit,
                            updated_at = (select current_timestamp)
                       WHERE affiliates.id = affiliate_id_reg and ((affiliates.registration <>record_row.matricula_tit) or  affiliates.registration is null) and insert_text(record_row.matricula_tit) is not null ;
                       ----actualizacion de pension entity solo en caso de que sea null

                     UPDATE public.affiliates
                        set pension_entity_id = pension_entity_id_reg,
                            updated_at = (select current_timestamp)
                     WHERE affiliates.id = affiliate_id_reg and affiliates.pension_entity_id is null;

                  ------actualizacion del estado del afiliado
                     UPDATE public.affiliates
                        set affiliate_state_id =  (CASE WHEN record_row.clase_renta_dh='VIUDEDAD'  THEN affiliate_state_id_reg_fall ELSE affiliate_state_id_reg_jub end),
                            updated_at = (select current_timestamp)
                     WHERE affiliates.id = affiliate_id_reg and affiliates.affiliate_state_id is null;

                  ------actualizacion solo en caso de que tenga su viuda y su estado del afiliado este diferente de fallecido 

                     UPDATE public.affiliates
                        set affiliate_state_id =  affiliate_state_id_reg_fall,
                            updated_at = (select current_timestamp)
                        WHERE affiliates.id = affiliate_id_reg and affiliates.affiliate_state_id <> (CASE WHEN record_row.clase_renta_dh='VIUDEDAD'  THEN affiliate_state_id_reg_fall else affiliates.affiliate_state_id end);
                   ----- actualizacion de la fecha de fallecimiento del afiliado solo en caso de ser null y su estado este como fallecido

                      UPDATE public.affiliates
                         set date_death = record_row.fec_fail_tit,
                             updated_at = (select current_timestamp)
                      WHERE affiliates.id = affiliate_id_reg and record_row.fec_fail_tit is not null and affiliates.date_death is null ;
                        ----actualizacion de fecha de fallecimiento solo en caso de que sea nulo
                      UPDATE public.affiliates
                         set birth_date =  record_row.fecha_nacimiento_tit,
                             updated_at = (select current_timestamp)
                       WHERE affiliates.id = affiliate_id_reg and affiliates.birth_date is null and record_row.fecha_nacimiento_tit is not null;
              END IF;
       END LOOP;
       return 'Datos del afiliado actualizados';
    end;
     $$ LANGUAGE plpgsql;");

   
//
        DB::statement("CREATE OR REPLACE FUNCTION public.tmp_create_affiliate_senasir(db_name_intext text)
RETURNS character varying
LANGUAGE plpgsql
AS $$
DECLARE
      type_state varchar;
      message varchar;
      user_id_reg integer := 1;
      pension_entity_id_reg integer :=5;
      affiliate_state_id_reg_fall  integer :=4;
      affiliate_state_id_reg_jub  integer :=5;
      count_created_affiliate integer := 0;
      cant varchar ;
       
       -- Declaración EXPLICITA del cursor
      cur_payroll CURSOR for (select * from dblink(db_name_intext,'SELECT id,id_person_senasir,matricula_tit,carnet_tit,num_com_tit,concat_carnet_num_com_tit,
      p_nombre_tit,s_nombre_tit,paterno_tit,materno_tit,ap_casada_tit,fecha_nacimiento_tit,
      genero_tit,fec_fail_tit,matricula_dh,carnet_dh,num_com_dh,concat_carnet_num_com_dh,
      p_nombre_dh,s_nombre_dh,paterno_dh,materno_dh,ap_casada_dh,fecha_nacimiento_dh,
      genero_dh,fec_fail_dh,clase_renta_dh,state,observacion FROM copy_person_senasirs')
      as  copy_person_senasirs(id integer,id_person_senasir integer ,matricula_tit character varying(250),carnet_tit character varying(250),num_com_tit character varying(250),concat_carnet_num_com_tit character varying(250),
      p_nombre_tit character varying(250),s_nombre_tit character varying(250),paterno_tit character varying(250),materno_tit character varying(250),ap_casada_tit character varying(250),fecha_nacimiento_tit date,
      genero_tit character varying(250),fec_fail_tit date,matricula_dh character varying(250),carnet_dh character varying(250),num_com_dh character varying(250),concat_carnet_num_com_dh character varying(250),
      p_nombre_dh character varying(250),s_nombre_dh character varying(250),paterno_dh character varying(250),materno_dh character varying(250),ap_casada_dh character varying(250),fecha_nacimiento_dh date,
      genero_dh character varying(250),fec_fail_dh date,clase_renta_dh character varying(250),state character varying(250),observacion character varying)
      where copy_person_senasirs.state='unrealized' and copy_person_senasirs.concat_carnet_num_com_tit is not null);
       
 begin
      --************************************************************
      --*Funcion creacion de afiliados*******************
      --************************************************************
      -- Procesa el cursor
      FOR record_row IN cur_payroll loop
      IF (quantity_identity_card(record_row.concat_carnet_num_com_tit) = 0) then
        INSERT INTO affiliates (user_id,affiliate_state_id,pension_entity_id,id_person_senasir,
        first_name, second_name, last_name, mothers_last_name,surname_husband ,
        identity_card, registration,date_death,gender,created_at,updated_at)
        VALUES (user_id_reg,(CASE WHEN record_row.clase_renta_dh='VIUDEDAD'  THEN affiliate_state_id_reg_fall ELSE affiliate_state_id_reg_jub end),
        pension_entity_id_reg,record_row.id_person_senasir,
        insert_text(record_row.p_nombre_tit),
        insert_text(record_row.s_nombre_tit),
        insert_text(record_row.paterno_tit),
        insert_text(record_row.materno_tit),
        insert_text(record_row.ap_casada_tit),
        insert_text(record_row.concat_carnet_num_com_tit),
        insert_text(record_row.matricula_tit),
        record_row.fec_fail_tit,
        record_row.genero_tit,
        current_timestamp,
        current_timestamp);
        type_state:='AFILIADO_CREADO';
        count_created_affiliate:= count_created_affiliate + 1;
        cant:=  (select dblink_exec(db_name_intext, 'UPDATE copy_person_senasirs SET state=''accomplished'',observacion='''||type_state||''' WHERE copy_person_senasirs.id= '||record_row.id||''));  
     END IF;
    END LOOP;
   return count_created_affiliate;
 end;
$$;
");
DB::statement("CREATE OR REPLACE FUNCTION public.tmp_create_spouse_senasir(db_name_intext text)
RETURNS character varying
LANGUAGE plpgsql
AS $$
DECLARE
      type_state varchar;
      message varchar;
      user_id_reg integer := 1;
      pension_entity_id_reg integer :=5;
      affiliate_state_id_reg_fall  integer :=4;
      affiliate_state_id_reg_jub  integer :=5;
      count_create_spouse integer := 0;
      cant varchar ;

       -- Declaración EXPLICITA del cursor
      cur_payroll CURSOR for (select * from affiliates a,dblink(db_name_intext,'SELECT id,id_person_senasir,matricula_tit,carnet_tit,num_com_tit,concat_carnet_num_com_tit,
      p_nombre_tit,s_nombre_tit,paterno_tit,materno_tit,ap_casada_tit,fecha_nacimiento_tit,
      genero_tit,fec_fail_tit,matricula_dh,carnet_dh,num_com_dh,concat_carnet_num_com_dh,
      p_nombre_dh,s_nombre_dh,paterno_dh,materno_dh,ap_casada_dh,fecha_nacimiento_dh,
      genero_dh,fec_fail_dh,clase_renta_dh,state,observacion FROM copy_person_senasirs')
      as  copy_person_senasirs(id_copy integer,id_person_senasir integer ,matricula_tit character varying(250),carnet_tit character varying(250),num_com_tit character varying(250),concat_carnet_num_com_tit character varying(250),
      p_nombre_tit character varying(250),s_nombre_tit character varying(250),paterno_tit character varying(250),materno_tit character varying(250),ap_casada_tit character varying(250),fecha_nacimiento_tit date,
      genero_tit character varying(250),fec_fail_tit date,matricula_dh character varying(250),carnet_dh character varying(250),num_com_dh character varying(250),concat_carnet_num_com_dh character varying(250),
      p_nombre_dh character varying(250),s_nombre_dh character varying(250),paterno_dh character varying(250),materno_dh character varying(250),ap_casada_dh character varying(250),fecha_nacimiento_dh date,
      genero_dh character varying(250),fec_fail_dh date,clase_renta_dh character varying(250),state character varying(250),observacion character varying)
      where copy_person_senasirs.state='accomplished' and copy_person_senasirs.clase_renta_dh ='VIUDEDAD'
     and a.id_person_senasir = copy_person_senasirs.id_person_senasir);

 begin
      --************************************************************
      --*Funcion creacion de esposas*******************
      --************************************************************
      -- Procesa el cursor
      FOR record_row IN cur_payroll loop
      IF not exists (select * from spouses where affiliate_id = record_row.id) then
         IF(insert_text(record_row.concat_carnet_num_com_dh) is not null) then
         message:=  'crear esposa de un afiliado ya registrado';
        INSERT INTO public.spouses(user_id, affiliate_id,identity_card,registration, last_name, mothers_last_name , first_name , second_name, created_at,updated_at, birth_date)
        VALUES (user_id_reg,record_row.id,
        insert_text(record_row.concat_carnet_num_com_dh),
        insert_text(record_row.matricula_dh),
        insert_text(record_row.paterno_dh),
        insert_text(record_row.materno_dh),
        insert_text(record_row.p_nombre_dh),
        insert_text(record_row.s_nombre_dh),
        current_timestamp,
        current_timestamp,
        record_row.fecha_nacimiento_dh);
        count_create_spouse:= count_create_spouse +1;
       END IF;
      END IF;
    END LOOP;
   return count_create_spouse;
 end;
$$;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
