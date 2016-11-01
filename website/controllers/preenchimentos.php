<?php
    class Preenchimentos extends CI_Controller 
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Candidato_model');
            $this->load->model('Preenchimento_model');
        }

        public function dadospessoais()
        {
            log_message('debug', 'Valida Dados Pessoais');

            $this->form_validation->set_rules('nome', 'Nome', 'min_length[10]|required');
            $this->form_validation->set_rules('cpf', 'CPF', 'exact_length[14]|required|cpf_check');
            $this->form_validation->set_rules('celular', 'Celular', 'exact_length[15]|required');
            $this->form_validation->set_rules('data_nascimento', 'Data de Nascimento', 'min_length[10]|max_length[10]|required|data_check');
            $this->form_validation->set_rules('sexo', 'Sexo', 'required');
            $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'required');
            $this->form_validation->set_rules('cidade', 'Cidade', 'min_length[10]|required');
            $this->form_validation->set_rules('cep', 'CEP', 'exact_length[9]|required');
            $this->form_validation->set_rules('endereco', 'Endereço', 'min_length[10]|required');

            $this->form_validation->set_message('cpf_check', 'CPF digitado é inválido');
            $this->form_validation->set_message('data_check', 'Data de Nascimento é inválida');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                log_message('debug', $this->input->raw_input_stream);

                $arr = array('nome' => form_error('nome', '', ''),
                             'cpf' => form_error('cpf', '', ''),
                              'celular' => form_error('celular', '', ''),
                              'data_nascimento' => form_error('data_nascimento', '', ''),
                              'sexo' => form_error('sexo', '', ''),
                              'estado_civil' => form_error('estado_civil', '', ''),
                              'cidade' => form_error('cidade', '', ''),
                              'cep' => form_error('cep', '', ''),
                              'endereco' => form_error('endereco', '', ''),
                              'msg_dadospessoais' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'nome' => $this->input->post('nome'),
                    'cpf' =>  only_numbers($this->input->post('cpf')),
                    'celular' =>  only_numbers($this->input->post('celular')),
                    'observacao_celular' =>  $this->input->post('observacao_celular'),
                    'telefone_fixo' =>  only_numbers($this->input->post('telefone_fixo')),
                    'observacao_telefone_fixo' =>  $this->input->post('observacao_telefone_fixo'),
                    'data_nascimento' =>  $this->input->post('data_nascimento'),
                    'idestado_civil' =>  $this->input->post('estado_civil'),
                    'idsexo' =>  $this->input->post('sexo'),
                    'cep' =>  only_numbers($this->input->post('cep')),
                    'endereco' =>  $this->input->post('endereco'),
                    'bairro' =>  $this->input->post('bairro'),
                    'idcidade' =>  $this->input->post('id_cidade'),
                    'numero' =>  $this->input->post('numero'),
                    'complemento' =>  $this->input->post('complemento'),
                    'pode_viajar' =>  $this->input->post('pode_viajar'),
                    'pode_mudar' =>  $this->input->post('pode_mudar'),
                    'deficiente' =>  $this->input->post('deficiente'),
                    'observacoes' => $this->input->post('observacoes_dadospessoais')
                );

                $this->Candidato_model->update_dados_pessoais($arr);
            }
        }

        public function historicoacademico()
        {
            log_message('debug', 'Valida Dados Acadêmicos');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('formacao', 'Formação', 'required|greater_than[-1]');
            $this->form_validation->set_rules('curso', 'Curso', 'min_length[10]|required');
            $this->form_validation->set_rules('situacao', 'Situação', 'required');
            $this->form_validation->set_rules('inicio', 'Mês e ano de início', 'exact_length[7]|required|data_check_ma');
            $this->form_validation->set_rules('instituicao', 'Nome da Instituição', 'min_length[10]|required');
            $this->form_validation->set_rules('local', 'Local', 'min_length[10]|required');

            $situacao = $this->input->post('situacao');
            if($situacao == '1' || $situacao == '2')
            {
                $this->form_validation->set_rules('final', 'Mês e ano de término', 'required|data_check_ma');
            }

            $this->form_validation->set_message('data_check_ma', 'Mês e ano inválidos');
            $this->form_validation->set_message('greater_than', 'Selecione uma formação válida');

            log_message('debug', 'inicio' . $this->input->post('inicio') . ' ' . $_POST['inicio']);

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('formacao' => form_error('formacao', '', ''),
                             'curso' => form_error('curso', '', ''),
                              'situacao' => form_error('situacao', '', ''),
                              'inicio' => form_error('inicio', '', ''),
                              'final' => form_error('final', '', ''),
                              'instituicao' => form_error('instituicao', '', ''),
                              'local' => form_error('local', '', ''),
                              'msg_historicoacademico' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'idprofissional' => '72',
                    'nome_instituicao' => $this->input->post('instituicao'),
                    'idformacao' =>  $this->input->post('formacao'),
                    'curso' =>  $this->input->post('curso'),
                    'situacao' =>  $this->input->post('situacao'),
                    'inicio' =>  $this->input->post('inicio'),
                    'final' =>  $this->input->post('final'),
                    'local' =>  $this->input->post('local')
                );

                $this->Candidato_model->update_formacao_academica($arr);
            }
        }

        public function historicoprofissional()
        {
            log_message('debug', 'Valida Dados Profissionais');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('setor', 'Setor', 'required|greater_than[-1]');
            $this->form_validation->set_rules('nivel', 'Nivel Hierárquico', 'required|greater_than[-1]');
            $this->form_validation->set_rules('jornada', 'Jornada', 'required|greater_than[-1]');
            $this->form_validation->set_rules('tipo_contrato', 'Tipo de Contrato', 'required|greater_than[-1]');
            $this->form_validation->set_rules('inicio', 'Mês e ano de início', 'exact_length[7]|required|data_check_ma');
            $this->form_validation->set_rules('empresa', 'Nome da Empresa', 'min_length[10]|required');
            $this->form_validation->set_rules('local', 'Local', 'min_length[10]|required');
            $this->form_validation->set_rules('cargo', 'Cargo', 'min_length[10]|required');
            $this->form_validation->set_rules('atividades', 'Atividades', 'min_length[20]|required');


            $situacao = $this->input->post('situacao');
            if($situacao == '0')
            {
                $this->form_validation->set_rules('final', 'Mês e ano de término', 'required|data_check_ma');
            }

            $this->form_validation->set_message('data_check_ma', 'Mês e ano inválidos');
            $this->form_validation->set_message('greater_than', 'Selecione um ítem válido');

            log_message('debug', 'inicio' . $this->input->post('inicio') . ' ' . $_POST['inicio']);

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('setor' => form_error('setor', '', ''),
                             'nivel' => form_error('nivel', '', ''),
                              'jornada' => form_error('jornada', '', ''),
                              'tipo_contrato' => form_error('tipo_contrato', '', ''),
                              'inicio' => form_error('inicio', '', ''),
                              'empresa' => form_error('empresa', '', ''),
                              'local' => form_error('local', '', ''),
                              'cargo' => form_error('cargo', '', ''),
                              'atividades' => form_error('atividades', '', ''),
                              'final' => form_error('final', '', ''),
                              'msg_historicoprofissional' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'idprofissional' => '72',
                    'nome_empresa' => $this->input->post('empresa'),
                    'idcargo' =>  '-1',
                    'funcao' =>  $this->input->post('cargo'),
                    'inicio' =>  $this->input->post('inicio'),
                    'final' =>  $this->input->post('final'),
                    'situacao' =>  $this->input->post('situacao'),
                    'atividades' =>  $this->input->post('atividades'),
                    'ultimo_salario' =>  $this->input->post('salario'),
                    'informar' =>  $this->input->post('informar'),
                    'idtipo_contrato' =>  $this->input->post('tipo_contrato'),
                    'idsetor' =>  $this->input->post('setor'),
                    'idjornada' =>  $this->input->post('jornada'),
                    'idnivel_hierarquico' =>  $this->input->post('nivel'),
                    'local' =>  $this->input->post('local')
                );

                $beneficio = $this->input->post('beneficio');

                $this->Candidato_model->update_historico_profissional($arr, $beneficio);
            }
        }

        public function conhecimento_duplicado($str)
        {
            $user = '72';
            return $this->Candidato_model->check_conhecimento_user($str, $user);
        }

        public function idioma()
        {
            log_message('debug', 'Valida Idioma');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('idioma', 'Idioma', 'required|callback_conhecimento_duplicado');

            $this->form_validation->set_message('conhecimento_duplicado', 'Idioma já existente');

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
                    'idprofissional' => '72',
                    'nivel' => $this->input->post('nivel_idioma'),
                    'idconhecimento' =>  (empty($this->input->post('ididioma'))) ? '-1' : $this->input->post('ididioma'),
                    'conhecimento' => $this->input->post('idioma')
                );

                $this->Candidato_model->update_idioma($arr);
            }
        }

        public function informatica()
        {
            log_message('debug', 'Valida Idioma');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('informatica', 'Conhecimento', 'required|callback_conhecimento_duplicado');
            $this->form_validation->set_rules('qtd_tempo_informatica', 'Tempo de Conhecimento', 'required');

            $this->form_validation->set_message('conhecimento_duplicado', 'Conhecimento já existente');

            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('informatica' => form_error('informatica', '', ''),
                             'tempo_informatica' => form_error('qtd_tempo_informatica', '', ''),
                              'msg_idioma' => 'Erro');
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'idprofissional' => '72',
                    'tempo' => $this->input->post('qtd_tempo_informatica') . ' ' . $this->input->post('tempo_informatica'),
                    'idconhecimento' =>  (empty($this->input->post('idinformatica'))) ? '-1' : $this->input->post('idinformatica'),
                    'conhecimento' => $this->input->post('informatica')
                );

                $this->Candidato_model->update_informatica($arr);
            }
        }

        public function objetivo()
        {
            log_message('debug', 'Valida Objetivo');

            log_message('debug', $this->input->raw_input_stream);

            $this->form_validation->set_rules('setor', 'Setor', 'required|greater_than[-1]');
            $this->form_validation->set_rules('nivel1', 'Nivel Hierárquico', 'required|greater_than[-1]');
            $this->form_validation->set_rules('jornada', 'Jornada', 'required|greater_than[-1]');
            $this->form_validation->set_rules('tipo_contrato', 'Tipo de Contrato', 'required|greater_than[-1]');
            $this->form_validation->set_rules('cargo', 'Cargo', 'min_length[10]|required');
            $this->form_validation->set_rules('salario_menor', 'Menor Salario Desejado', 'menorquecampo[salario_maior]');
            $this->form_validation->set_rules('salario_maior', 'Maior Salario Desejado', 'maiorquecampo[salario_menor]');

            $this->form_validation->set_message('greater_than', 'Selecione um ítem válido');
            $this->form_validation->set_message('menorquecampo', 'O valor do campo {field} deve ser menor ou igual ao valor do campo {param}.');
            $this->form_validation->set_message('maiorquecampo', 'O valor do campo {field} deve ser maior ou igual ao valor do campo {param}.');
            
            if ($this->form_validation->run() == FALSE) 
            {
                log_message('debug', 'Falhou na validacao');

                $arr = array('setor' => form_error('setor', '', ''),
                             'nivel' => form_error('nivel', '', ''),
                              'jornada' => form_error('jornada', '', ''),
                              'tipo_contrato' => form_error('tipo_contrato', '', ''),
                              'cargo' => form_error('cargo', '', ''),
                              'salario' => form_error('salario_menor', '', '') . ' ' . form_error('salario_maior', '', ''));
                
                echo json_encode($arr);
            }
            else
            {
                $arr = array(
                    'idprofissional' => '72',
                    'idcargo' =>  '-1',
                    'funcao' =>  $this->input->post('cargo'),
                    'idtipo_contrato' =>  $this->input->post('tipo_contrato'),
                    'idsetor' =>  $this->input->post('setor'),
                    'idjornada' =>  $this->input->post('jornada'),
                    'idespecializacao' =>  '-1',
                    'idnivel_hierarquico1' =>  $this->input->post('nivel1'),
                    'idnivel_hierarquico2' =>  $this->input->post('nivel2'),
                    'salario_menor' =>  $this->input->post('salario_menor'),
                    'salario_maior' =>  $this->input->post('salario_maior'),
                    'exibe_salario' =>  $this->input->post('exibe_salario')
                );

                $this->Candidato_model->update_objetivo($arr);
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

        public function busca_local()
        {
            $local = $this->input->get('term');

            log_message('debug', 'busca_local. Param1: ' . $local);

            if(strlen($local) > 2)
            {
                $result = $this->Preenchimento_model->busca_local($local);
                if(count($result) > 0)
                {
                    foreach($result as $localizacao)
                    {
                        $arr_curso[] = $localizacao->localizacao;
                    }
                    
                    echo json_encode($arr_curso);
                }
            }
        }

        public function busca_idioma()
        {
            $idioma = $this->input->get('term');
            $profissional = '72';

            log_message('debug', 'busca_idioma. Param1: ' . $idioma);

            if(strlen($idioma) > 2)
            {
                $result = $this->Preenchimento_model->busca_idioma($idioma, $profissional);
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
            $profissional = '72';

            log_message('debug', 'busca_informatica. Param1: ' . $informatica);

            if(strlen($informatica) > 2)
            {
                $result = $this->Preenchimento_model->busca_informatica($informatica, $profissional);
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
    }