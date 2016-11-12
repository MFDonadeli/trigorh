<?php
    class Preenchimentos_vaga extends CI_Controller 
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Candidato_model');
            $this->load->model('Vaga_model');
            $this->load->model('Preenchimento_model');
        }

        public function dados_vaga()
        {
            log_message('debug', 'Valida Dados Pessoais');

            $this->form_validation->set_rules('titulo', 'Titulo', 'min_length[10]|required');
            $this->form_validation->set_rules('cargo', 'Cargo', 'min_length[10]|required');
            $this->form_validation->set_rules('setor', 'Setor', 'required|greater_than[-1]');
            $this->form_validation->set_rules('nivel', 'Nivel Hierárquico', 'required|greater_than[-1]');
            $this->form_validation->set_rules('jornada', 'Jornada', 'required|greater_than[-1]');
            $this->form_validation->set_rules('tipo_contrato', 'Tipo de Contrato', 'required|greater_than[-1]');
            $this->form_validation->set_rules('cep', 'CEP', 'exact_length[9]|required');
            $this->form_validation->set_rules('descricao', 'Descrição', 'min_length[10]|required');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                log_message('debug', $this->input->raw_input_stream);

                $arr = array('titulo' => form_error('titulo', '', ''),
                             'cargo' => form_error('cargo', '', ''),
                              'setor' => form_error('setor', '', ''),
                              'nivel' => form_error('nivel', '', ''),
                              'jornada' => form_error('jornada', '', ''),
                              'tipo_contrato' => form_error('tipo_contrato', '', ''),
                              'cep' => form_error('cep', '', ''),
                              'descricao' => form_error('descricao', '', ''),
                              'msg_dados_vagas' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'idempresa' => $this->session->userdata('user_id'),
                    'idvaga' => $this->input->post('vaga'),
                    'titulo' =>  $this->input->post('titulo'),
                    'cargo' =>  $this->input->post('cargo'),
                    'quantidade' =>  $this->input->post('quantidade'),
                    'idtipo_contrato' =>  $this->input->post('tipo_contrato'),
                    'idsetor' =>  $this->input->post('setor'),
                    'idjornada' =>  $this->input->post('jornada'),
                    'idescala' =>  $this->input->post('escala'),
                    'idnivel_hierarquico' =>  $this->input->post('nivel'),
                    'descricao' =>  $this->input->post('descricao'),
                    'cep_vaga' =>  only_numbers($this->input->post('cep')),
                    'endereco' =>  $this->input->post('endereco'),
                    'bairro' =>  $this->input->post('bairro'),
                    'cidade' =>  $this->input->post('cidade'),
                    'salario_menor' =>  $this->input->post('salario_menor'),
                    'salario_maior' =>  $this->input->post('salario_maior'),
                    'periodicidade' =>  $this->input->post('periodicidade'),
                    'exibe_salario' =>  $this->input->post('exibe_salario'),
                    'idade_menor' =>  $this->input->post('idade_menor'),
                    'idade_maior' =>  $this->input->post('idade_maior'),
                    'precisa_viajar' =>  $this->input->post('precisa_viajar'),
                    'precisa_mudar' =>  $this->input->post('precisa_mudar'),
                    'para_deficiente' =>  $this->input->post('deficiente'),
                    'necessita_experiencia' =>  $this->input->post('sem_experiencia'),
                    'primeiro_emprego' =>  $this->input->post('primeiro_emprego'),
                    'temporario' =>  $this->input->post('trabalho_temporario'),
                    'urgente' =>  $this->input->post('urgente')
                );

                $beneficio = $this->input->post('beneficio');

                $this->Vaga_model->update_dados_vaga($arr, $beneficio);
            }
        }


        public function idioma($id)
        {
            log_message('debug', 'Valida Idioma');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('idioma', 'Idioma', 'required');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('idioma' => form_error('idioma', '', ''),
                              'msg_idioma' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'id_vaga' => $id,
                    'nivel' => $this->input->post('nivel_idioma'),
                    'idconhecimento' =>  (empty($this->input->post('ididioma'))) ? '-1' : $this->input->post('ididioma'),
                    'conhecimento' => $this->input->post('idioma'),
                    'obrigatorio' => $this->input->post('obrigatorio')
                );

                $ididioma = $this->input->post('id_idioma');

                $this->Vaga_model->update_idioma($arr, $ididioma);
            }
        }

        public function informatica($id)
        {
            log_message('debug', 'Valida Informatica Vaga');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('informatica', 'Conhecimento', 'required');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('informatica' => form_error('informatica', '', ''),
                              'msg_informatica' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'id_vaga' => $id,
                    'tempo' => $this->input->post('qtd_tempo_informatica') . ' ' . $this->input->post('tempo_informatica'),
                    'idconhecimento' =>  (empty($this->input->post('idinformatica'))) ? '-1' : $this->input->post('idinformatica'),
                    'conhecimento' => $this->input->post('informatica'),
                    'obrigatorio' => $this->input->post('obrigatorio')
                );

                $idinformatica = $this->input->post('id_informatica');

                $this->Vaga_model->update_informatica($arr, $idinformatica);
            }
        }

        public function curso($id)
        {
            log_message('debug', 'Valida Curso');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('formacao', 'Formação', 'required|greater_than[-1]');
            $this->form_validation->set_rules('situacao', 'Situação', 'required');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                 $arr = array('formacao' => form_error('formacao', '', ''),
                             'curso' => form_error('curso', '', ''),
                              'situacao' => form_error('situacao', '', ''),
                              'obrigatorio' => form_error('obrigatorio', '', ''),
                              'msg_curso' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                if(empty($this->input->post('curso')))
                {
                    
                }

                $arr = array(
                    'idvaga' => $id,
                    'idformacao' =>  $this->input->post('formacao'),
                    'curso' =>  $this->input->post('curso'),
                    'situacao' =>  $this->input->post('situacao'),
                    'obrigatorio' =>  $this->input->post('obrigatorio')
                );

                $idcurso = $this->input->post('id_curso');

                $this->Vaga_model->update_curso($arr, $idcurso);
            }
        }


        public function busca_curso()
        {
            $curso = $this->input->get('curso');
            $formacao = $this->input->get('formacao');

            log_message('debug', 'busca_curso. Param1: ' . $formacao . ' Param2: ' . $curso);
            log_message('debug', print_r($_GET, true));

            if(strlen($curso) > 2)
            {
                $result = $this->Preenchimento_model->busca_curso($formacao, $curso);
                if(count($result) > 0)
                {
                    foreach($result as $curso)
                        $arr_curso[] = $curso->curso;
                    
                    echo json_encode($arr_curso);
                }
            }
        }

        public function busca_idioma()
        {
            $idioma = $this->input->get('term');
            $profissional = $this->session->userdata('user_id');

            log_message('debug', 'busca_idioma. Param1: ' . $idioma);

            if(strlen($idioma) > 2)
            {
                $result = $this->Vaga_model->busca_idioma($idioma);
                log_message('debug', 'busca idioma.' . print_r($result, true));
                
                if(count($result) > 0)
                {
                    foreach($result as $idioma_result)
                    {
                        $arr_idioma[] = array(
                            'value' => $idioma_result->idconhecimento,
                            'label' => $idioma_result->conhecimento
                        );
                    }
                        //$arr_idioma[$idioma_result->idconhecimento] = $idioma_result->conhecimento;
                    
                    $str = json_encode($arr_idioma);

                    log_message('debug', 'busca idioma. Json: ' . $str);

                    echo $str;
                }
            }
        }

        public function busca_informatica()
        {
            $informatica = $this->input->get('term');
            $profissional = $this->session->userdata('user_id');

            log_message('debug', 'busca_informatica. Param1: ' . $informatica);

            if(strlen($informatica) > 2)
            {
                $result = $this->Vaga_model->busca_informatica($informatica);
                log_message('debug', 'busca idioma.' . print_r($result, true));
                
                if(count($result) > 0)
                {
                    foreach($result as $informatica_result)
                    {
                        $arr_informatica[] = array(
                            'value' => $informatica_result->idconhecimento,
                            'label' => $informatica_result->conhecimento
                        );
                    }
                        //$arr_idioma[$idioma_result->idconhecimento] = $idioma_result->conhecimento;
                    
                    $str = json_encode($arr_informatica);

                    log_message('debug', 'busca_informatica. Json: ' . $str);

                    echo $str;
                }
            }
        }


//****** IDIOMA
        public function get_idioma($id)
        {
            log_message('debug', 'get_idioma');

            $id_idioma = str_replace('id_', '', $this->input->post('id_idioma'));

            $result = $this->Vaga_model->get_idioma($id, $id_idioma);

            $arr_idioma = json_decode(json_encode($result), true);

            $arr_idioma['id_idioma'] = $arr_idioma['idconhecimento_vaga'];
            
            $str = json_encode($arr_idioma);

            log_message('debug', 'Json: ' . $str);

            echo $str;
        }

        public function get_idiomas($id)
        {
            log_message('debug', 'get_idiomas');

            $result = $this->Vaga_model->get_idiomas($id);

            if(count($result) > 0)
            {   
                $data['dados'] = $result;
                $data['div_id'] = 'div_idioma';

                $html = $this->load->view('vagas/get_idiomas.php', $data, true);

                echo $html;
            }
        }

        public function apaga_idioma($id)
        {
            $id_idioma = str_replace('id_', '', $this->input->post('id_idioma'));

            log_message('debug', 'apaga_idioma. Param1: ' . $id . " Param2:" . $id_idioma);

            $result = $this->Vaga_model->apaga_idioma($id, $id_idioma);    
        }

        //****** INFORMATICA
        public function get_informatica($id)
        {
            log_message('debug', 'get_informatica');
            
            $id_informatica = str_replace('id_', '', $this->input->post('id_informatica'));

            $result = $this->Vaga_model->get_informatica($id, $id_informatica);
            
            $arr_informatica = json_decode(json_encode($result), true);

            preg_match('/[^\d]+/', $arr_informatica['tempo'], $textMatch);
            preg_match('/\d+/', $arr_informatica['tempo'], $numMatch);

            $arr_informatica['tempo_grandeza'] = trim($textMatch[0]);
            $arr_informatica['tempo_numero'] = trim($numMatch[0]);
            $arr_informatica['id_informatica'] = $arr_informatica['idconhecimento_vaga'];

            $str = json_encode($arr_informatica);

            echo $str;
        }

        public function get_informaticas($id)
        {
            log_message('debug', 'get_informaticas');

            $result = $this->Vaga_model->get_informaticas($id);

            if(count($result) > 0)
            {   
                $data['dados'] = $result;
                $data['div_id'] = 'div_informatica';

                $html = $this->load->view('vagas/get_informatica.php', $data, true);

                echo $html;
            }
        }

        public function apaga_informatica($id)
        {
            $id_informatica = str_replace('id_', '', $this->input->post('id_informatica'));

            log_message('debug', 'apaga_informatica. Param1: ' . $id . " Param2:" . $id_informatica);

            $result = $this->Vaga_model->apaga_idioma($id, $id_informatica);  
        }

//****** CURSO
        public function get_curso($id)
        {
            log_message('debug', 'get_curso');

            $id_curso = str_replace('id_', '', $this->input->post('id_curso'));

            $result = $this->Vaga_model->get_curso($id, $id_curso);
            
            $str = json_encode($result);

            log_message('debug', 'Json: ' . $str);

            echo $str;
        }

        public function get_cursos($id)
        {
            log_message('debug', 'get_cursos. Param1: ' . $id);

            $result = $this->Vaga_model->get_cursos($id);

            if(count($result) > 0)
            {   
                $data['dados'] = $result;
                $data['div_id'] = 'div_idioma';

                $html = $this->load->view('vagas/get_cursos.php', $data, true);

                echo $html;
            }
        }

        public function apaga_curso($id)
        {
            $id_idioma = str_replace('id_', '', $this->input->post('id_curso'));

            log_message('debug', 'apaga_idioma. Param1: ' . $id . " Param2:" . $id_idioma);

            $result = $this->Vaga_model->apaga_curso($id, $id_curso);    
        }
        
    }

