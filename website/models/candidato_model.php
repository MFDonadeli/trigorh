<?php
    class Candidato_model extends CI_Model
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->helper('password_helper');
        }

        /**
        * login_user
        *
        * Verifica se o par usuario e senha são existentes
        *
        * @param	string
        * @param	string 
        * @return	boolean
        */
        public function login_user($email,$senha)
        {   
            log_message('debug', 'login_user. Param1: {' . $email . '} Param2: {' . $senha . '}');

            //Validate
            $this->db->where('email',$email);
            
            $result = $this->db->get('usuario');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            if($result->num_rows() == 1)
            {
                if(!check_password($senha, $result->row(0)->senha, $result->row(0)->salt))
                    return false;
    
                return $result->row(0)->idprofissional;
            } 
            else
            {
                return false;
            }
        }

        /**
        * has_user
        *
        * Verifica se existe ou não o profissional e seu tipo
        *
        * @param	string
        * @param	string 
        * @return	int O tipo do profissional ou 0 se o profissional não existir
        */
        public function has_user($email, $cpf)
        {
            log_message('debug', 'has_user. Param1: {' . $email . '} Param2: {' . $cpf . '}');

            $this->db->where('email',$email);
            $result = $this->db->get('usuario');

            $this->db->where('cpf', preg_replace('/[^0-9]/', '', (string) $cpf));
            $result_prof = $this->db->get('profissional');
            
            if($result->num_rows() > 0 || $result_prof->num_rows() > 0)
            {
                if($result->num_rows() > 0)
                {
                    if($result->row(0)->idprofissional != "-1")
                    {
                        return 1; //Já tem profissional
                    }
                    else if($result->row(0)->idempresa != "-1")
                    {
                        return 2; //Já tem empresa
                    }
                }

                return 3; //Já tem profissional, neste cpf
            }
            else
                return 0; //Não tem profissional
        }

        /**
        * get_user
        *
        * Traz id do profissional se existir na tabela
        *
        * @param	string
        * @param	string 
        * @return	mixed O id do profissional ou false se o profissional não existir
        */
        public function get_user($email, $cpf)
        {
            log_message('debug', 'get_user. Param1: {' . $email . '} Param2: {' . $cpf . '}');

            $this->db->where('email',$email);
            $result = $this->db->get('usuario');

            $this->db->where('cpf', preg_replace('/[^0-9]/', '', (string) $cpf));
            $result_prof = $this->db->get('profissional');
            
            if($result->num_rows() > 0 || $result_prof->num_rows() > 0)
            {
                return $result->row(0)->idprofissional;
            }
            else
                return false;
        }

        /**
        * insere_candidato
        *
        * Insere novo candidato no Banco de Dados, nas tabelas profissional e usuario
        *
        * @param	string
        * @param	string
        * @param	string
        * @param	string
        * @param	boolean
        * @return	mixed O id do profissional ou false se ocorreu algum erro
        */
        public function insere_candidato($nome, $email, $senha, $cpf, $empresa = false)
        {
            log_message('debug', 'insere_candidato. Param1: {' . $nome . '} Param2: {' . $email . '} Param3: {' . $senha . '} Param4: {' . $cpf . '} Param5: {' . $empresa . '}');

            $novo_profissional = array(
                'email' => $email,
                'ativo' => '1',
                'nome' => $nome,
                'cpf' => preg_replace('/[^0-9]/', '', (string) $cpf)
            );

            $this->db->insert('profissional', $novo_profissional);

            $insert_id = $this->db->insert_id();

            $this->registra_acao($insert_id, Acao::CADASTRO_CV);

            $pass_pair = gerar_senha($senha);

            if($empresa)
            {
                $this->db->set('idprofissional', $insert_id);
                $this->db->where('idempresa', $empresa);
                $this->db->update('usuario');
            }
            else
            {
                $novo_usuario = array(
                    'idprofissional' => $insert_id,
                    'idempresa' => '-1',
                    'email' => $email,
                    'senha' => $pass_pair[1],
                    'salt' => $pass_pair[0]
                );

                $this->db->insert('usuario', $novo_usuario);

                return $insert_id;
            }
            
            return false;
        }

        /**
        * atualiza_senha
        *
        * Atualiza senha do profissional no banco de dados
        *
        * @param	int
        * @param	string 
        * @return	boolean
        */
        public function atualiza_senha($id, $senha)
        {
            log_message('debug', 'atualiza_senha. Param1: {' . $id . '} Param2: {' . $senha . '}');

            $pass_pair = gerar_senha($senha);

            log_message('debug', 'senhas novas Senha: ' . $pass_pair[0] . ' Salt: ' . $pass_pair[1]);

            $this->db->set('senha', $pass_pair[0]);
            $this->db->set('salt', $pass_pair[1]);

            $this->db->where('idprofissional', $id);
            $this->db->update('usuario');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            $this->registra_acao($id, Acao::MUDOU_SENHA);
            
            return true;
        }

        
        /**
        * registra_acao
        *
        * Grava ação do profissional no site
        *
        * @param	int
        * @param	int (constante da classe Acao)
        * @return	string O hash da acao gravada no banco
        */
        public function registra_acao($id, $acao)
        {
            log_message('debug', 'registra_acao. Param1: {' . $id . '} Param2: {' . $acao . '}');

            $hash = sha1(date('Y-m-d H:i:s') . $id . $acao);

            $nova_acao = array(
                'idprofissional' => $id,
                'idacao' => $acao,
                'hash' => $hash
            );

            $this->db->insert('profissional_historico', $nova_acao);
            
            return $hash;
        }

        /**
        * check_pedido_senha
        *
        * Verifica se o pedido de alterar senha foi mesmo feito
        * Verificando se existe id do profissional que quer alterar senha, juntamente com um hash
        *
        * @param	id
        * @param	string
        * @return	boolean	true ou false se existir o pedido
        */
        public function check_pedido_senha($id, $hash)
        {
            log_message('debug', 'check_pedido_senha. Param1: {' . $id . '} Param2: {' . $hash . '}');

            $this->db->where('idprofissional',$id);
            $this->db->where('hash',$hash);
            $result = $this->db->get('profissional_historico');

            if($result->num_rows() > 0)
            {
                return true;
            }
            else
                return false;    
        }

        /**
        * update_dados_pessoais
        *
        * Atualiza dados pessoais de profissional
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_dados_pessoais($arr)
        {
            log_message('debug', 'update_dados_pessoais');

            $this->db->where('cpf', $arr['cpf']);

            $this->db->update('profissional', $arr);

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * get_dados_pessoais
        *
        * Busca dados pessoais de profissional
        *
        * @param	int
        * @return	array
        */
        public function get_dados_pessoais($id)
        {
            log_message('debug', 'get_dados_pessoais. Param1: ' . $id);

            $this->db->select('profissional.*, localizacao.localizacao');
            $this->db->from('profissional');
            $this->db->join('localizacao', 
                'profissional.idcidade = localizacao.id');
            $this->db->where('profissional.idprofissional', $id);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * update_formacao_academica
        *
        * Insere/Atualiza dados de formação acadêmica do profissional
        *
        * @param	array
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_formacao_academica($arr, $idhistoricoacademico)
        {
            log_message('debug', 'update_formacao_academica');

            if(empty($idhistoricoacademico))
                $this->db->insert('formacao_academica', $arr);
            else
            {
                $this->db->where('idformacao_academica', $idhistoricoacademico);
                $this->db->update('formacao_academica', $arr);
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * apaga_formacao_academica
        *
        * Apaga dados de formação acadêmica do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_formacao_academica($id, $idformacao_academica)
        {
            log_message('debug', 'apaga_formacao_academica');

            $this->db->where('idformacao_academica', $idformacao_academica);
            $this->db->where('idprofissional', $id);
            $this->db->delete('formacao_academica');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * get_formacao_academica
        *
        * Busca dados pessoais de profissional
        *
        * @param	int
        * @param	int
        * @return	array
        */
        public function get_formacao_academica($id, $idformacao)
        {
            log_message('debug', 'get_formacao_academica. Param1: ' . $id . ' Param2: ' . $idformacao);

            $this->db->where('idprofissional', $id);
            $this->db->where('idformacao_academica', $idformacao);
            $result = $this->db->get('formacao_academica');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_formacoes_academica
        *
        * Busca dados pessoais de profissional
        *
        * @param	int
        * @return	array
        */
        public function get_formacoes_academicas($id)
        {
            log_message('debug', 'get_formacoes_academicas. Param1: ' . $id);

            $this->db->select('formacao_academica.idformacao_academica, formacao_academica.curso, formacao.formacao');
            $this->db->from('formacao_academica');
            $this->db->join('formacao', 
                'formacao_academica.idformacao = formacao.idformacao');
            $this->db->where('formacao_academica.idprofissional', $id);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * update_historico_profissional
        *
        * Insere/Atualiza dados de formação acadêmica do profissional
        *
        * @param	array
        * @param	array
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_historico_profissional($arr, $beneficio, $idhistoricoprofissional)
        {
            log_message('debug', 'update_historico_profissional. ID' . $idhistoricoprofissional);

            if(empty($idhistoricoprofissional))
            {
                $this->db->insert('historico_profissional', $arr);

                log_message('debug', 'Last Query: ' . $this->db->last_query()); 

                $insert_id = $this->db->insert_id();

                if(is_array($beneficio))
                {
                    foreach($beneficio as $benef)
                    {
                        $arrbenef = array(
                            'idhistorico_profissional' => $insert_id,
                            'idbeneficio' => $benef
                        );
                        $this->db->insert('beneficio_historico', $arrbenef);
                    }
                } 
            }
            else
            {
                $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
                $this->db->update('historico_profissional', $arr);

                if(is_array($beneficio))
                {
                    $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
                    $this->db->delete('beneficio_historico');

                    foreach($beneficio as $benef)
                    {
                        $arrbenef = array(
                            'idhistorico_profissional' => $idhistoricoprofissional,
                            'idbeneficio' => $benef
                        );
                        $this->db->insert('beneficio_historico', $arrbenef);
                    }
                } 
            }  
        }

        /**
        * apaga_formacao_academica
        *
        * Apaga dados de formação acadêmica do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_historico_profissional($id, $idhistoricoprofissional)
        {
            log_message('debug', 'apaga_historico_profissional');

            $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
            $this->db->delete('beneficio_historico');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
            $this->db->where('idprofissional', $id);
            $this->db->delete('historico_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * get_historico_profissional
        *
        * Busca dados do histórico profissional de profissional
        *
        * @param	int
        * @param	int
        * @return	array
        */
        public function get_historico_profissional($id, $idhistoricoprofissional)
        {
            log_message('debug', 'get_historico_profissional. Param1: ' . $id . ' Param2: ' . $idhistoricoprofissional);

            $this->db->where('idprofissional', $id);
            $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
            $result = $this->db->get('historico_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_historico_profissional_beneficio
        *
        * Busca benefícios de um histórico profissional de profissional
        *
        * @param	int
        * @return	array
        */
        public function get_historico_profissional_beneficio($idhistoricoprofissional)
        {
            log_message('debug', 'get_historico_profissional_beneficio. Param1: ' . $idhistoricoprofissional);

            $this->db->where('idhistorico_profissional', $idhistoricoprofissional);
            $result = $this->db->get('beneficio_historico');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        /**
        * get_formacoes_academica
        *
        * Busca dados pessoais de profissional
        *
        * @param	int
        * @return	array
        */
        public function get_historicos_profissionais($id)
        {
            log_message('debug', 'get_historicos_profissionais. Param1: ' . $id);

            $this->db->where('idprofissional', $id);
            $result = $this->db->get('historico_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * update_idioma
        *
        * Insere/Atualiza dados de formação acadêmica do profissional
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_idioma($arr, $ididioma)
        {
            log_message('debug', 'update_idioma. Idioma: ' . $ididioma);

            if(empty($ididioma))
            {
                $this->db->insert('conhecimento_profissional', $arr);
            }
            else
            {
                $this->db->where('idconhecimento_profissional', $ididioma);
                $this->db->update('conhecimento_profissional', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * update_idioma
        *
        * Insere/Atualiza dados de formação acadêmica do profissional
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_informatica($arr, $idinformatica)
        {
            log_message('debug', 'update_idioma');

            if(empty($idinformatica))
            {
                $this->db->insert('conhecimento_profissional', $arr);
            }
            else
            {
                $this->db->where('idconhecimento_profissional', $idinformatica);
                $this->db->update('conhecimento_profissional', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());

        }

        /**
        * check_conhecimento_user
        *
        * Verifica se existe o conhecimento no banco
        *
        * @param	string
        * @param	string
        * @return	boolean	false se conhecimento existir
        */
        public function check_conhecimento_user($str, $user)
        {
            log_message('debug', 'check_conhecimento_user. Param1: {' . $str . '} Param2: {' . $user . '}');

            /*$this->db->select('conhecimento.conhecimento');
            $this->db->from('conhecimento_profissional');
            $this->db->join('conhecimento', 
                'conhecimento_profissional.idconhecimento = conhecimento.idconhecimento');
            $this->db->where('conhecimento.conhecimento', $str);
            $this->db->where('conhecimento_profissional.idprofissional', $user);
            $result = $this->db->get();
            */

            $this->db->where('idprofissional',$user);
            $this->db->where('conhecimento',$str);
            $result = $this->db->get('conhecimento_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            if($result->num_rows() > 0)
            {
                return false;
            }
            else
                return true; 
        }

        /**
        * update_objetivo
        *
        * Insere/Atualiza dados de objetivo do profissional
        *
        * @param	array
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_objetivo($arr, $idobjetivo)
        {
            log_message('debug', 'update_objetivo');

            if(empty($idobjetivo))
            {
                $this->db->insert('objetivo_profissional', $arr);
            }
            else
            {
                $this->db->where('idobjetivo_profissional', $idobjetivo);
                $this->db->update('objetivo_profissional', $arr);    
            }

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        
        /**
        * get_idioma
        *
        * Seleciona um idioma do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_idioma($id, $id_idioma)
        {
            log_message('debug', 'get_idioma. Param1: ' . $id . ' Param2: ' . $id_idioma);

            $this->db->select('conhecimento_profissional.idconhecimento_profissional, conhecimento_profissional.idconhecimento,
                                conhecimento_profissional.idprofissional, conhecimento_profissional.conhecimento, 
                                conhecimento_profissional.nivel, nivel_idioma.nivel_idioma');
            $this->db->from('conhecimento_profissional');
            $this->db->join('nivel_idioma', 
                'conhecimento_profissional.nivel = nivel_idioma.idnivel_idioma');
            $this->db->where('conhecimento_profissional.idprofissional', $id);
            $this->db->where('conhecimento_profissional.idconhecimento_profissional', $id_idioma);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_idiomas
        *
        * Busca os idiomas do profissional
        *
        * @param	int
        * @return	array
        */
        public function get_idiomas($id)
        {
            log_message('debug', 'get_idiomas. Param1: ' . $id);

            $this->db->select('conhecimento_profissional.*, nivel_idioma.nivel_idioma');
            $this->db->from('conhecimento_profissional');
            $this->db->join('conhecimento', 
                'conhecimento_profissional.idconhecimento = conhecimento.idconhecimento');
            $this->db->join('nivel_idioma', 
                'conhecimento_profissional.nivel = nivel_idioma.idnivel_idioma');
            $this->db->where('conhecimento.idtipo_conhecimento = 2');
            $this->db->where('conhecimento_profissional.idprofissional', $id);
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_idioma
        *
        * Apaga dados de idioma do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_idioma($id, $id_idioma)
        {
            log_message('debug', 'apaga_idioma');

            $this->db->where('idconhecimento_profissional', $id_idioma);
            $this->db->where('idprofissional', $id);
            $this->db->delete('conhecimento_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

//****** INFORMATICA

        /**
        * get_informatica
        *
        * Seleciona um conhecimento de informática do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_informatica($id, $id_informatica)
        {
            log_message('debug', 'get_idioma. Param1: ' . $id . ' Param2: ' . $id_informatica);

            $this->db->where('conhecimento_profissional.idprofissional', $id);
            $this->db->where('conhecimento_profissional.idconhecimento_profissional', $id_informatica);
            $result = $this->db->get('conhecimento_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_idiomas
        *
        * Busca os conhecimento de informática do profissional
        *
        * @param	int
        * @return	array
        */
        public function get_informaticas($id)
        {
            log_message('debug', 'get_idiomas. Param1: ' . $id);

            $this->db->select('conhecimento_profissional.*, subtipo_conhecimento.subtipo_conhecimento');
            $this->db->from('conhecimento_profissional');
            $this->db->join('conhecimento', 
                'conhecimento_profissional.idconhecimento = conhecimento.idconhecimento');
            $this->db->join('subtipo_conhecimento', 
                'conhecimento.idsubtipo_conhecimento = subtipo_conhecimento.idsubtipo_conhecimento');
            $this->db->where('conhecimento.idtipo_conhecimento = 1');
            $this->db->where('conhecimento_profissional.idprofissional', $id);
            $this->db->order_by('subtipo_conhecimento.subtipo_conhecimento');
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_informática
        *
        * Apaga dados de informática do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_informatica($id, $id_informatica)
        {
            log_message('debug', 'apaga_informática');

            $this->db->where('idconhecimento_profissional', $id_informatica);
            $this->db->where('idprofissional', $id);
            $this->db->delete('conhecimento_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

//******OBJETIVO

        /**
        * get_objetivo
        *
        * Seleciona um objetivo do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function get_objetivo($id, $id_objetivo)
        {
            log_message('debug', 'get_idioma. Param1: ' . $id . ' Param2: ' . $id_objetivo);

            $this->db->where('idprofissional', $id);
            $this->db->where('idobjetivo_profissional', $id_objetivo);
            $result = $this->db->get('objetivo_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->row();
        }

        /**
        * get_objetivo
        *
        * Busca os objetivos do profissional
        *
        * @param	int
        * @return	array
        */
        public function get_objetivos($id)
        {
            log_message('debug', 'get_idiomas. Param1: ' . $id);

            $this->db->where('idprofissional', $id);
            $result = $this->db->get('objetivo_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();    
        }

        /**
        * apaga_objetivo
        *
        * Apaga dados de objetivo do profissional
        *
        * @param	int
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function apaga_objetivo($id, $id_objetivo)
        {
            log_message('debug', 'apaga_objetivo');

            $this->db->where('idobjetivo_profissional', $id_objetivo);
            $this->db->where('idprofissional', $id);
            $this->db->delete('objetivo_profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());
        }

        /**
        * update_porcentagem_curriculo
        *
        * Atualiza porcentagem de preenchimento do currículo do profissional
        *
        * @param	int
        * @return	boolean	true ou false se existir o pedido
        */
        public function update_porcentagem_curriculo($id)
        {
            log_message('debug', 'update_porcentagem_curriculo. Param1: ' . $id);

            $this->db->select("count(*)");
            $this->db->where("idprofissional", $id);
            $query1 = $this->db->get_compiled_select("profissional");

            $this->db->select("count(*)");
            $this->db->where("idprofissional", $id);
            $query2 = $this->db->get_compiled_select("historico_profissional");

            $this->db->select("count(*)");
            $this->db->where("idprofissional", $id);
            $query3 = $this->db->get_compiled_select("formacao_academica");

            $this->db->select("count(*)");
            $this->db->where("idprofissional", $id);
            $query4 = $this->db->get_compiled_select("objetivo_profissional");

            $this->db->select("count(*)");
            $this->db->from("conhecimento_profissional cp");
            $this->db->join("conhecimento c", "cp.idconhecimento = c.idconhecimento");
            $this->db->where("c.idtipo_conhecimento = 1");
            $this->db->where("cp.idprofissional", $id);
            $query5 = $this->db->get_compiled_select();

            $this->db->select("count(*)");
            $this->db->from("conhecimento_profissional cp");
            $this->db->join("conhecimento c", "cp.idconhecimento = c.idconhecimento");
            $this->db->where("c.idtipo_conhecimento = 2");
            $this->db->where("cp.idprofissional", $id);
            $query6 = $this->db->get_compiled_select();

            $result = $this->db->query("SELECT (" .
                            $query1 . ") as profissional, (" .
                            $query2 . ") as historico_profissional, (" .
                            $query3 . ") as formacao_academica, (" .
                            $query4 . ") as objetivo_profissional, (" .
                            $query5 . ") as idioma, (" .
                            $query6 . ") as informatica " );

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            $porcentagem = 0;

            if($result->row()->profissional > 0) $porcentagem += Acao::CURRICULO_DADOS_PESSOAIS;
            if($result->row()->historico_profissional > 0) $porcentagem += Acao::CURRICULO_FORMACAO_ACADEMICA;
            if($result->row()->formacao_academica > 0) $porcentagem += Acao::CURRICULO_EXPERIENCIA_PROFISSIONAL;
            if($result->row()->idioma > 0) $porcentagem += Acao::CURRICULO_IDIOMA;
            if($result->row()->informatica > 0) $porcentagem += Acao::CURRICULO_INFORMATICA;
            if($result->row()->objetivo_profissional > 0) $porcentagem += Acao::CURRICULO_OBJETIVO;

            $this->db->set('porcentagem_cadastro', $porcentagem);
            $this->db->where('idprofissional', $id);
            $this->db->update('profissional');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            $this->registra_acao($id, Acao::ATUALIZA_CV);
        }

        /**
        * informacao_basica
        *
        * Traz nome, porcentagem do currículo preenchido e data da última atualização do currículo
        *
        * @param	int
        * @return	array
        */
        public function informacao_basica($id)
        {
            log_message('debug', 'informacao_basica. Param1: ' . $id);

            $this->db->select('p.nome, p.porcentagem_cadastro, ph.data');
            $this->db->from('profissional p');
            $this->db->join('profissional_historico ph', 'p.idprofissional = ph.idprofissional');
            $this->db->where('p.idprofissional', $id);
            $this->db->where('ph.idacao = 2');
            $this->db->order_by('ph.idprofissional_historico', 'DESC');
            $this->db->limit(1);
            $result = $this->db->get();

            return $result->row();
        }


    }
?>