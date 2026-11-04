# CineHub

Portal internacional de entretenimento com foco em descoberta de filmes, séries, games, curiosidades, histórias reais e reviews.

Este arquivo é a referência central do projeto. Toda alteração futura deve respeitar as decisões, regras e padrões descritos aqui.

## 1. Objetivo do projeto

O CineHub combina, em uma mesma publicação:

- trailer ou vídeo oficial;
- sinopse;
- review editorial;
- informações da obra;
- comentários e reações do público.

O conteúdo é publicado somente pela administração. Visitantes podem consumir, curtir, comentar e, futuramente, sugerir pautas.

O projeto será internacional, começando por:

- Estados Unidos: `/en/` — USD ($)
- Reino Unido: `/en/` — GBP (£)
- Austrália: `/en/` — AUD (A$)
- Canadá: `/en/` — CAD (C$)
- Japão: `/ja-jp/` — JPY (¥), mantido atualmente como locale de teste visual de regionalização.

Os quatro países de língua inglesa compartilham **uma única versão editorial em inglês** e **uma única URL pública `/en`**. Os caminhos anteriores `/en-us/`, `/en-gb/`, `/en-au/` e `/en-ca/` são redirecionados para `/en/` (inclusive páginas internas e parâmetros de busca). As moedas acima identificam os mercados de interesse; a URL compartilhada não determina o país geográfico de quem acessa.

É um único projeto, um único código-base e um único domínio. Não devem ser criados sites separados por região. A estrutura de localizações continua disponível para idiomas e mercados futuros.

## 2. Stack atual

- PHP 8.2+
- Laravel 12
- Blade
- Bootstrap 5
- Bootstrap Icons
- Vite
- MySQL no ambiente local (XAMPP)

A stack só deve ser alterada quando houver necessidade técnica real e decisão registrada neste README.

## 3. Regras obrigatórias de interface

Antes de iniciar um novo lote ou alteração, ler este README e consultar o lote anterior para continuar exatamente de onde o projeto parou. Não recomeçar estruturas já decididas nem alterar padrões aprovados sem solicitação explícita. Nas entregas por lote, enviar somente os arquivos novos ou ajustados.

1. Usar Bootstrap como primeira opção para layout, componentes, espaçamento, responsividade e utilitários.
2. Usar Bootstrap Icons para os ícones do projeto.
3. CSS próprio somente quando Bootstrap não resolver adequadamente.
4. Manter somente um arquivo principal de estilo: `resources/css/app.css`.
5. Evitar bibliotecas visuais adicionais sem necessidade.
6. Manter a aplicação responsiva e mobile-first.
7. A Home atual é referência visual aprovada.
8. O layout visual da Home não pode ser alterado sem solicitação explícita.
9. A Home poderá receber dados reais no futuro, desde que aparência, organização e comportamento visual sejam preservados.

## 4. SEO é requisito estrutural

Toda funcionalidade deve ser pensada também para posicionamento orgânico no Google.

SEO não deve ser tratado como ajuste posterior.

O projeto deve manter:

- URLs descritivas e estáveis;
- HTML semântico;
- títulos e descrições únicos;
- canonical correto, incluindo paginação e filtros editoriais indexáveis;
- `hreflang` entre versões públicas distintas de idioma/região, sem criar quatro URLs inglesas equivalentes;
- sitemap XML;
- robots.txt;
- conteúdo indexável sem depender de JavaScript pesado;
- imagens otimizadas;
- carregamento rápido;
- Core Web Vitals como referência de qualidade;
- dados estruturados apenas quando correspondem ao conteúdo real;
- links internos entre conteúdos relacionados;
- páginas de categorias úteis para busca;
- distinção clara entre conteúdo editorial e publicidade.

O objetivo é aumentar tráfego orgânico e preparar o projeto para monetização futura com Google AdSense sem prejudicar velocidade, leitura ou experiência.

## 5. Regra de conteúdo e reutilização

Não devem ser criados CRUDs separados apenas para alimentar blocos diferentes da Home.

Uma publicação é cadastrada uma vez e pode aparecer automaticamente em várias áreas conforme seus dados e métricas.

Exemplos:

- **Latest**: data de publicação;
- **Most Liked**: quantidade de curtidas;
- **Trending**: combinação de atividade recente, como visualizações, curtidas e comentários;
- **Categories**: categorias vinculadas à publicação;
- **Featured**: marcação editorial específica quando necessário.

O banco deve ser a fonte única da verdade.

## 6. Estrutura editorial planejada

Entidades principais preparadas no projeto:

### `posts`
Registro principal da publicação.

Responsável por estado, autor, destaque e datas de publicação.

### `post_localizations`
Conteúdo regional/localizado de uma publicação.

Pode conter:

- título;
- slug;
- resumo;
- sinopse;
- review;
- spoilers, ocultos por padrão na página pública;
- SEO title;
- SEO description.

Isso evita duplicar completamente uma publicação para cada país. Um registro em `posts` continua podendo ter localizações independentes, como `en-us` e `ja-jp`. Na estratégia atual, `en-us` é a **chave interna compartilhada** para o conteúdo em inglês exibido em `/en` nos Estados Unidos, Reino Unido, Austrália e Canadá. As versões antigas `en-gb`, `en-au` e `en-ca` permanecem armazenadas para compatibilidade e histórico, sem exigir novos cadastros separados. Outros idiomas podem ter localização própria futuramente.

### `categories`
Registro base das categorias editoriais.

### `category_localizations`
Nome, slug, descrição e SEO de cada categoria por país/locale.

### `category_post`
Relacionamento entre publicações e categorias.

### `media`
Vídeos, trailers, thumbnails e respectivas fontes.

### `comments`
Comentários e moderação.

### `reactions`
Curtidas e futuras reações.

### `post_views`
Visualizações usadas para métricas e tendências.

### `page_engagements`
Coleta de duração da visita à página, tempo ativo de engajamento e sinal de sessão engajada para calcular a taxa de rejeição. A tabela é criada pela migration específica `2026_11_04_000100_create_page_engagements_table.php`; seus dados começam a existir após a implantação e não são inventados retroativamente.

### `suggestions`
Sugestões de pauta enviadas futuramente pelos visitantes.

## 7. Estados de publicação

O projeto trabalha somente com:

- `draft`: rascunho, não aparece publicamente;
- `published`: publicado, aparece publicamente quando a data de publicação é válida.

A data de publicação vem preenchida automaticamente no cadastro e pode ser alterada para uma data anterior quando necessário. Não existe status separado para agendamento ou arquivamento.

## 8. Regionalização

Configuração central em `config/localization.php`.

Atualmente, os endereços públicos e as chaves internas são:

- `/en` — inglês compartilhado entre Estados Unidos, Reino Unido, Austrália e Canadá; utiliza `en-us` / `en_US` internamente.
- `/ja-jp` — japonês, com `ja-jp` / `ja_JP` independente, atualmente em teste.
- `en-gb`, `en-au` e `en-ca` — códigos históricos preservados no banco e na configuração para compatibilidade. Suas URLs antigas redirecionam para `/en`.

Regras:

1. Não duplicar conteúdo em inglês quando não houver diferença editorial real: um cadastro atende aos quatro países.
2. Manter a estrutura preparada para novos países/idiomas com conteúdo próprio quando necessário.
3. Manter URL pública, idioma e localização técnica coerentes, usando `/en` para o grupo inglês.
4. Usar `hreflang` apenas entre versões públicas distintas e equivalentes.
5. Não obrigar troca de região apenas por IP.
6. Uma versão de outro idioma só recebe URL indexável quando sua localização estiver ativa.
7. Não existe fallback público automático entre **idiomas independentes**: sem versão `ja-jp`, por exemplo, a publicação não aparece em `/ja-jp`. O inglês compartilhado não é considerado fallback entre países.
8. Imagem e vídeo do grupo `/en` usam a mídia da localização interna `en-us`; não usar mídia de outro idioma como fallback.
9. Categorias do grupo `/en` usam a localização interna em inglês; outros idiomas exigem sua versão ativa.

## 9. Painel administrativo

O painel administrativo fica em `/admin`.

Regras:

- acesso somente autenticado;
- acesso administrativo somente para usuários com `is_admin = true`;
- sessão regenerada no login;
- logout invalida a sessão;
- não expor criação pública de conta administrativa;
- usuários administrativos devem ser criados de forma controlada.

O painel possui CRUD de categorias e publicações. Mídia e SEO são gerenciados dentro do fluxo editorial, sem criar cadastros paralelos desnecessários.

## 10. Segurança

Toda implementação deve considerar:

- validação de entrada;
- proteção CSRF;
- escaping do Blade;
- prevenção de XSS e injeções;
- rate limiting quando aplicável;
- moderação de comentários;
- proteção contra curtidas automatizadas;
- autenticação forte no admin;
- login administrativo limitado a 5 tentativas por minuto por origem;
- logs e backups em produção;
- nenhuma senha ou segredo versionado.

## 11. Vídeos e imagens

- Preferir vídeos oficiais incorporados.
- Não baixar e republicar trailers sem autorização.
- Registrar fonte quando necessário.
- Imagens e thumbnails devem possuir uso permitido.
- Imagens enviadas pelo admin são redimensionadas e convertidas para WebP quando o servidor possui suporte GD; sem GD, o upload original continua funcionando como fallback.
- No futuro, vídeos devem usar carregamento sob demanda para preservar performance.
- Na postagem individual, imagem e vídeo ficam centralizados e limitados a 900 px de largura; imagens têm limite visual de 520 px de altura.
- O fluxo público de vídeo usa provedores controlados; URL genérica de embed não deve ser aceita.

## 12. Publicidade

A estrutura deve permitir Google AdSense futuramente.

Regras:

- publicidade discreta;
- anúncio nunca deve parecer botão de reprodução;
- anúncios não podem destruir Core Web Vitals;
- conteúdo editorial deve continuar sendo o foco principal;
- consentimento e políticas aplicáveis devem ser respeitados.
- anúncios devem permanecer separados do player e dos controles de interação.

## 13. Organização do código

Preferir responsabilidades claras:

- Controllers: coordenação da requisição;
- Models: relacionamentos, scopes e comportamento de domínio simples;
- Middleware: regras transversais de acesso/localização;
- Blade: apresentação;
- Config: decisões configuráveis;
- Migrations: estrutura do banco;
- Seeders: dados iniciais controlados.

Evitar lógica complexa diretamente nas views.

## 14. O que já está construído

- Home responsiva aprovada visualmente e preservada;
- navegação principal, dark/light e URL pública compartilhada `/en` para US/UK/AU/CA, com `/ja-jp` independente e redirecionamentos das URLs inglesas antigas;
- SEO base, sitemap, canonical e `hreflang`, com canonical próprio para paginação e filtros de gênero;
- autenticação e proteção do painel administrativo;
- CRUD de categorias, posts e mídia;
- SEO por categoria e por localização de post;
- estados simplificados `draft` e `published`;
- data de publicação preenchida automaticamente no cadastro;
- tipo de conteúdo removido das postagens; a categoria passa a definir a classificação editorial;
- SEO de categorias preenchido automaticamente a partir do nome e da descrição, permanecendo editável;
- imagens, trailers, fontes, alt text e ordenação;
- Home usando posts reais sem alteração visual;
- páginas públicas de categorias, busca, post e relacionados;
- busca pública global com sugestões em tempo real, resultados agrupados e navegação por teclado, inclusive na Home desktop e mobile;
- tema escuro como padrão e tema claro com contraste próprio para todos os componentes principais;
- seção de spoilers por localização, recolhida por padrão e aberta somente por escolha do visitante;
- comentários com moderação;
- curtidas persistentes e visualizações deduplicadas;
- **Most Liked** e **Trending** por métricas reais;
- **Trending Now** e **Popular This Week** usam cache curto de 2 minutos por locale para reduzir consultas sem deixar rankings longamente desatualizados;
- sugestões públicas de pauta e moderação administrativa;
- formulário de contato e caixa administrativa;
- painel interno de métricas, incluindo tempo médio na página, tempo médio de engajamento e taxa de rejeição, com exportação JSON para IA;
- páginas de privacidade, termos e cookies;
- preferência de Analytics armazenada por cookie;
- integração estrutural com Google Analytics, ativada apenas com ID configurado e consentimento concedido;
- meta de verificação do Google Search Console configurável por ambiente;
- configurações administrativas para SaaS, doação e área do livro;
- área pública do livro com capa, sinopse, amostra e compra;
- gerenciamento estrutural de espaços publicitários, sem ativar AdSense;
- logs de alterações administrativas;
- cabeçalhos básicos de segurança;
- rate limiting e honeypot em formulários públicos sensíveis;
- comando de backup local para SQLite e agendamento opcional;
- testes estruturais de acesso administrativo, páginas públicas e cabeçalhos de segurança.

## 15. O que não faz parte desta etapa

- ativação real do Google AdSense;
- cadastro de conteúdo editorial real para lançamento.

As páginas legais incluídas são modelos estruturais e precisam de revisão jurídica adequada antes do lançamento público. Em produção, backups do provedor/hospedagem continuam recomendados mesmo quando o comando local estiver habilitado.

## 16. Aplicando esta estrutura

Depois de substituir os arquivos atualizados:

```bash
php artisan optimize:clear
php artisan migrate --seed
php artisan storage:link
npm run build
```

Para criar o primeiro administrador, preencher `ADMIN_NAME`, `ADMIN_EMAIL` e `ADMIN_PASSWORD` no `.env` e executar:

```bash
php artisan db:seed --class=AdminUserSeeder
```

Integrações opcionais:

```env
GOOGLE_SITE_VERIFICATION=
GOOGLE_ANALYTICS_ID=
BACKUP_ENABLED=false
```

Backup manual em SQLite:

```bash
php artisan cinehub:backup
```

Nunca versionar senha real, chaves ou segredos.

## 17. Estado dos lotes

- **Lote 1:** Admin, CRUDs, mídia e SEO — concluído.
- **Lote 2:** Área pública, busca, categorias, posts, relacionados e regiões — concluído.
- **Lote 3:** Comentários, moderação, curtidas, visualizações e Trending — concluído.
- **Lote 4:** Sugestões, métricas, legais, contato, segurança, logs, backups, integrações e finalização estrutural — concluído.

A Home permanece visualmente idêntica ao layout aprovado.

## 18. Regra para futuras IAs e desenvolvedores

Antes de alterar o projeto:

1. ler este README;
2. verificar o padrão já existente;
3. não reestruturar partes aprovadas sem necessidade;
4. não alterar visualmente a Home sem autorização explícita;
5. reutilizar Bootstrap e Bootstrap Icons;
6. manter apenas `resources/css/app.css` como CSS principal;
7. considerar SEO, performance, segurança e regionalização em cada nova funcionalidade;
8. atualizar este README quando uma decisão arquitetural, regra ou etapa relevante mudar.
## 19. Regras de interação pública

- Apenas usuários autenticados com e-mail verificado podem comentar, responder, curtir e favoritar. Visitas e leitura continuam livres.
- A identificação técnica de visitante usa hash e não armazena IP puro no banco de reações/visualizações.
- Curtidas são vinculadas ao `user_id`, com alternância curtir/descurtir e limite de uma reação por usuário/post/locale; comentários também podem receber curtidas.
- Comentários classificados como seguros são aprovados automaticamente; suspeitos ficam `pending`, visíveis somente ao autor logado até revisão do administrador; graves são recusados no servidor.
- Comentários marcados como `spam` nunca aparecem no site.
- Visualizações repetidas do mesmo visitante em menos de 6 horas não aumentam o contador.
- Bots conhecidos não devem aumentar visualizações.
- Rotas públicas de comentário e curtida usam rate limiting.
- Interações e métricas continuam registrando o `locale` técnico. Novos acessos em `/en` usam `en-us`; dados históricos em `en-us`, `en-gb`, `en-au` e `en-ca` são agregados no grupo público `en`, sem apagar os registros originais.
- **Trending Now** usa atividade recente do grupo de idioma; **Popular This Week** usa atividade dos últimos 7 dias do grupo. Outros idiomas, como `ja-jp`, mantêm indicadores próprios.
- Uma URL `/en` não identifica o país físico do visitante. Métricas geográficas individuais dos quatro países exigiriam coleta de localização adicional, que não faz parte desta unificação.


## 20. Regras de integrações e privacidade

- `GOOGLE_SITE_VERIFICATION` adiciona apenas a meta de verificação do Search Console.
- `GOOGLE_ANALYTICS_ID` não carrega Analytics sem consentimento `granted`.
- Preferências de Analytics são controladas na página de cookies.
- Ad slots apenas reservam posições; AdSense continua desativado nesta etapa.
- A Home não recebe banner, bloco ou alteração visual por causa dessas integrações.

## 21. Regras atuais de cadastro editorial

- Não existe campo `type` em postagens.
- Categorias são a classificação editorial principal.
- Status disponíveis: `draft` e `published`.
- `draft` nunca aparece publicamente.
- `published` aparece publicamente quando possui data de publicação válida.
- A data de publicação é preenchida com a data/hora atual no formulário e pode ser alterada para uma data anterior.
- No cadastro de categoria, slug, SEO title e SEO description possuem geração automática, mas continuam editáveis.

### Ajuste administrativo — SEO automático
- Categorias são sempre ativas; o controle manual de ativo/inativo foi removido do formulário.
- SEO de postagens é preenchido automaticamente por região: título SEO usa o título da postagem e descrição SEO usa resumo, sinopse ou fallback.
- Os campos SEO continuam editáveis manualmente.
- A geração automática também ocorre no backend para não depender de JavaScript.

### Ajuste administrativo — países das postagens
- Estados Unidos, Reino Unido, Austrália e Canadá compartilham uma única versão editorial em inglês, acessível por `/en`. Não criar quatro conteúdos ingleses equivalentes.
- A chave técnica principal de conteúdo em inglês continua `en-us`; o formulário e o fluxo editorial devem trabalhar com essa versão compartilhada para os quatro mercados.
- O controle “Use a specific version for this region” permanece removido.
- A localização padrão em inglês continua obrigatória; localizações de outros idiomas, como `ja-jp`, são opcionais e independentes.
- Uma localização adicional passa a ter versão ativa quando seu título é preenchido; se ficar sem título, permanece inativa.
- Existe apenas um registro principal em `posts`; `post_localizations` preserva título, slug, resumo, sinopse, review e SEO por idioma/localização, sem duplicar a postagem inteira.
- Localizações antigas `en-gb`, `en-au` e `en-ca` são mantidas no banco para compatibilidade e histórico, mas não precisam ser preenchidas para novos conteúdos do grupo `/en`.
- Conteúdos de idiomas distintos continuam sendo preparados manualmente no admin. Não há API de IA nem tradução automática paga.
- Textos fixos da interface usam o sistema de tradução do Laravel para o idioma selecionado; o conteúdo do banco é responsabilidade editorial da respectiva localização.
- Os códigos técnicos permanecem no banco/configuração para compatibilidade; a URL pública dos quatro mercados ingleses é somente `/en`.

## 22. Regionalização da Home por idioma

- A Home continua sendo um único arquivo e mantém exatamente o mesmo layout visual.
- A URL pública `/en` representa a Home compartilhada de Estados Unidos, Reino Unido, Austrália e Canadá; `/ja-jp` permanece independente para japonês.
- `SetLocale` associa `/en` à chave interna `en-us` e aplica o locale correspondente; textos fixos continuam resolvidos pelos arquivos JSON em `lang/`.
- Títulos de seções, busca, categorias padrão, descrições de categorias e SEO da Home são compartilhados em inglês no grupo `/en`; outras localizações podem ter textos próprios.
- As seis categorias padrão continuam regionalizadas pelo `slug`, evitando depender de um único nome global salvo em `categories`.
- Conteúdo editorial de `post_localizations` usa a versão inglesa compartilhada para `/en`, sem exigir quatro publicações; outros idiomas continuam independentes.
- Não duplicar a Home por país. Novos idiomas/localizações devem ser adicionados em `config/localization.php` e receber seus arquivos de idioma.
- URLs públicas antigas `/en-us`, `/en-gb`, `/en-au` e `/en-ca`, inclusive subcaminhos, redirecionam para `/en`.

## 23. Mídia por idioma/localização no cadastro de postagem

- A mídia principal continua sendo cadastrada no fluxo da respectiva localização da postagem.
- A versão inglesa compartilhada `/en` usa a mídia da chave interna `en-us` para Estados Unidos, Reino Unido, Austrália e Canadá.
- Outros idiomas/localizações, como `ja-jp`, podem escolher independentemente entre `Image` e `YouTube video`.
- Para YouTube, o admin recebe apenas a URL normal do vídeo; o sistema extrai o ID e monta o embed automaticamente.
- Não é necessário cadastrar quatro trailers ou imagens diferentes para o mesmo conteúdo em inglês. Mídias históricas de `en-gb`, `en-au` e `en-ca` permanecem armazenadas, sem uso como fallback público do grupo `/en`.
- Imagens continuam armazenadas no disco `public`, dentro da pasta da postagem/locale.
- O banco continua usando a entidade `media`; a unificação das URLs não exige nova migration.
- Para cada localização ativa, o formulário básico mantém uma mídia principal. Ao trocar imagem por YouTube (ou o contrário), a mídia principal daquela localização é substituída.
- Se não houver vídeo, a Home mostra a imagem sem alterar o layout aprovado.
- Se houver vídeo do YouTube e não houver imagem própria, os cards podem usar a thumbnail pública do vídeo como capa.

## 24. Locale japonês para auditoria visual

- `ja-jp` foi adicionado como locale de teste para facilitar a identificação de textos fixos que ainda não estejam regionalizados.
- A URL de teste é `/ja-jp` e o locale interno do Laravel é `ja_JP`.
- País: Japan; moeda: JPY (`¥`).
- Os textos já cobertos pelo sistema de tradução aparecem em japonês; textos que permanecerem em inglês ficam visualmente evidentes e devem ser revisados.
- O layout visual da Home não foi alterado.
- O conteúdo vindo do banco continua dependendo da versão `post_localizations` cadastrada para `ja-jp`.
- Se o Japão não fizer parte do lançamento, remover `ja-jp` antes da publicação definitiva para não expor uma versão regional de teste ao Google.

## 22. Regras estritas por idioma/localização

- Categorias seguem o mesmo modelo das postagens: uma categoria base e localizações em `category_localizations`.
- Nome, slug, descrição e SEO de categoria do grupo `/en` usam a versão inglesa compartilhada (`en-us`); outros idiomas podem ter localizações independentes.
- Se uma categoria não tiver versão ativa para o idioma da URL atual, ela não aparece nessa versão pública.
- Se uma postagem não tiver `post_localizations` ativo para o idioma da URL atual, ela não aparece nessa versão pública.
- Não existe fallback público de texto, categoria, imagem ou vídeo **entre idiomas distintos**. O compartilhamento de `/en` entre quatro países não representa fallback.
- Mídia do grupo `/en` utiliza `media.locale = en-us`; outras localizações utilizam sua própria mídia.
- Curtidas, comentários, visualizações e engajamento continuam registrando `locale`. O grupo público `en` reúne os registros históricos de `en-us`, `en-gb`, `en-au` e `en-ca` para indicadores e rankings, sem reescrevê-los.
- Destaque editorial é definido exclusivamente pela versão localizada (`post_localizations.is_featured`) nas telas, Home e relatórios; o campo legado de `posts` não deve orientar destaque.
- `Trending Now`: atividade recente do idioma/grupo (janela atual de 2 dias), ponderando visualizações, curtidas e comentários.
- `Latest Videos`: somente publicações do idioma/grupo com vídeo YouTube cadastrado para sua localização ativa, ordenadas pela publicação mais recente.
- `Popular This Week`: atividade dos últimos 7 dias do idioma/grupo, ponderando visualizações, curtidas e comentários.
- Sitemap só deve incluir categorias e postagens que realmente tenham versão pública ativa; os quatro países ingleses usam a URL `/en`.

## 25. Publicidade direta para anunciantes — planejamento futuro

Além do Google AdSense, o CineHub poderá vender espaços publicitários diretamente para empresas e anunciantes.

Direção planejada:

- criar uma página pública **Advertise with us** para contato comercial;
- atendimento inicialmente somente por texto/e-mail, sem necessidade de chamadas de vídeo;
- o anunciante envia empresa, produto/serviço, país, período desejado, orçamento, link de destino e material do anúncio;
- a administração revisa, traduz/adapta quando necessário e aprova o material antes da publicação;
- pagamento inicialmente poderá ser feito por cobrança/link enviado manualmente ao anunciante;
- antes da implementação financeira, avaliar o provedor mais adequado para cobranças internacionais, sem amarrar a arquitetura a uma empresa específica;
- futuramente o fluxo poderá evoluir para pagamento online e autoatendimento do anunciante;
- anúncios diretos devem ficar separados do Google AdSense;
- o espaço principal planejado é ao final das postagens, com apresentação visual integrada ao layout, responsiva e claramente identificada como **Publicidade / Sponsored**;
- anúncios poderão ter segmentação por país, período, categoria ou postagem específica;
- o painel administrativo deverá permitir ativar/desativar campanhas e controlar datas de início e fim;
- publicidade nunca deve prejudicar leitura, SEO, Core Web Vitals ou parecer parte enganosa do conteúdo editorial.

Esta funcionalidade não faz parte da etapa atual e deverá ser detalhada e melhorada antes da implementação.


## 26. Análise editorial assistida pelas métricas

O download JSON do painel de métricas deve ser suficiente para orientar uma IA sobre o que o CineHub deve publicar em seguida, sem depender de explicações adicionais fora do arquivo.

O painel e o JSON incluem, além de visualizações, visitantes únicos aproximados, curtidas e comentários aprovados:

- **Tempo médio na página:** média do tempo decorrido nas visitas medidas (`average_page_seconds`).
- **Tempo médio de engajamento:** média do tempo em que a página esteve ativa/em primeiro plano (`average_engaged_seconds`).
- **Taxa de rejeição:** porcentagem de visitas medidas sem ao menos 10 segundos ativos nem interação (`bounce_rate_percent`).
- Essas informações vêm de `page_engagements` e são apresentadas nos indicadores gerais e por conteúdo, respeitando os filtros de período e de idioma/grupo. Dados anteriores à coleta não devem ser preenchidos artificialmente.
- O grupo `/en` agrega registros das quatro antigas regiões inglesas. Essa agregação não deve ser apresentada como localização geográfica real dos visitantes.

A análise esperada deve:

- começar com um diagnóstico curto do que está crescendo, caindo, alcançando mais pessoas e gerando mais engajamento;
- comparar categorias, gêneros, gêneros de Blog, formatos de publicação e idiomas/grupos públicos, considerando o inglês `en` como uma única audiência editorial;
- tratar recomendações práticas de conteúdo como prioridade principal;
- identificar por que títulos com bom desempenho atraíram público, considerando gênero, tema, ambientação, estilo de gameplay, narrativa, atmosfera, franquia e interesse provável da audiência;
- sugerir títulos e temas semelhantes que possam atrair o mesmo público, inclusive fora da mesma franquia;
- explicar brevemente a relação entre o conteúdo vencedor e cada recomendação;
- recomendar, quando os dados permitirem, se a pauta deve ser post padrão ou artigo de Blog;
- separar sugestões entre publicar mais, testar em seguida e reduzir/despriorizar;
- não tirar conclusões confiáveis para idiomas/grupos com pouca ou nenhuma amostra, nem inferir o país real do visitante a partir da URL `/en`;
- usar `editorial_score` como apoio, sempre conferindo também views, engajamento, crescimento, confiança da amostra e quantidade de posts.

Exemplo de raciocínio esperado: se um conteúdo de **Middle-earth: Shadow of Mordor** ou **Shadow of War** tiver desempenho forte, a análise pode identificar afinidade com fantasia, atmosfera medieval, ação em mundo aberto e progressão de personagem e, a partir disso, sugerir conteúdos sobre **The Witcher 3**, **Skyrim**, **Elden Ring**, **Dragon Age** ou obras semelhantes.

O objetivo final não é apenas resumir métricas, mas transformá-las em um plano editorial claro sobre **o que publicar, o que testar e o que reduzir**.

## 27. Exportação de comentários para análise de audiência por IA

O painel de métricas possui um segundo download JSON dedicado aos comentários do público.

Regras:

- o mesmo seletor de período e idioma/grupo da tela de métricas controla a exportação de comentários; o grupo inglês reúne as quatro regiões históricas;
- o arquivo inclui o texto de cada comentário e o contexto da publicação em que ele foi feito;
- cada comentário informa data, status de moderação, locale público, relação de resposta e identificação técnica da publicação; `source_locale` preserva o código regional original dos registros históricos;
- o contexto da publicação inclui título, slug, formato (`standard` ou `blog`), categoria/tipo editorial, categorias, gêneros, categoria de Blog e gêneros de Blog quando existirem;
- e-mail do autor e outros identificadores diretos do visitante não são exportados;
- comentários aprovados têm maior peso para conclusões; pendentes podem ser avaliados com cautela e spam não deve orientar decisões editoriais;
- a IA deve identificar elogios, críticas, dúvidas, pedidos, temas recorrentes e assuntos que geram discussão;
- opiniões isoladas não devem ser tratadas como consenso sem repetição suficiente;
- quando um conteúdo receber reação positiva, a análise deve procurar oportunidades adjacentes e sugerir títulos/temas semelhantes capazes de interessar ao mesmo público;
- a análise deve diferenciar crítica à obra analisada de crítica ao próprio conteúdo/apresentação do CineHub;
- quando houver amostra suficiente, padrões podem ser separados por idioma/grupo; o grupo `en` não permite distinguir geograficamente os quatro países apenas pela URL.

O objetivo desse segundo relatório é complementar as métricas quantitativas com a **opinião textual do público**, permitindo que uma IA responda não apenas ao que está performando, mas também **por que o público gostou ou não gostou e o que ele demonstra querer consumir em seguida**.

A saída esperada da análise deve priorizar:

1. diagnóstico curto da opinião do público;
2. padrões positivos e negativos recorrentes;
3. pedidos e interesses explícitos;
4. recomendações concretas de próximos conteúdos e títulos relacionados;
5. dúvidas do público que podem virar pautas;
6. pontos que devem ser reduzidos, investigados ou corrigidos.


## 28. Contas de membros, moderação contextual e personalização

- O público pode ler o portal sem cadastro. Para curtir postagens/comentários, comentar, responder, salvar ou personalizar a experiência, exige-se somente conta autenticada. Não há verificação de e-mail no cadastro.
- Cadastro/login por e-mail e senha libera acesso imediatamente após criar a conta, sem mensagem de confirmação nem envio de e-mail de verificação. A recuperação de senha por e-mail continua opcional, somente quando solicitada pelo usuário, e precisa de `MAIL_*` configurado. Login Google é opcional via OAuth e só aparece com `GOOGLE_CLIENT_ID` e `GOOGLE_CLIENT_SECRET` definidos. No Google Cloud, configurar URI de retorno `https://DOMINIO/{locale}/account/google/callback` para cada locale público habilitado (incluindo `/en`); nunca enviar segredos ao frontend.
- O login administrativo anterior é preservado. Cadastro público jamais concede `is_admin`. A Home aprovada visualmente só ganhou links funcionais no menu já existente.
- Comentários e respostas utilizam a mesma moderação gratuita, pelo inglês compartilhado entre US/UK/AU/CA. Expressões informais e críticas a filmes/games não são bloqueadas só por conter palavrões. Regras levam em conta padrões de ataques, ameaças, pornografia, spam e disfarces, com limitações sem IA.
- No formulário, o navegador consulta a classificação enquanto o usuário digita: `blocked` desabilita o envio e mostra aviso abaixo do texto; após a correção, libera. O servidor reclassifica obrigatoriamente cada envio. `safe` vira `approved`; `review` vira `pending`, visível só ao próprio autor e à equipe administrativa no painel; `blocked` não é salvo. Aplicável a respostas também.
- Comentários públicos incluem respostas em um nível visual de profundidade; respostas a respostas entram no mesmo tópico. O admin aprova ou rejeita suspeitos em `/admin/comments`.
- Membros possuem favoritos por postagem e interesses por categorias, gêneros, temas de Blog e gêneros de Blog. A área `/for-you` reordena posts recentes conforme interesses explícitos e assuntos associados a likes e favoritos, sem tradução/fallback entre idiomas independentes; `/en` é compartilhado pelos quatro mercados ingleses. Não há envio de notificações push ou e-mails editoriais nesta etapa.
- As páginas da conta não são indexáveis (`noindex`), e comentários pendentes não contam para rankings públicos ou dados estruturados. Comentários antigos sem `user_id` continuam visíveis se já aprovados, mas não permitem login retroativo do autor.
- Antes de ativar em produção: executar as migrations novas; configurar credenciais OAuth opcionais, domínio/cookies HTTPS e validar o fluxo de cadastro imediato e moderação em staging. Configurar `MAIL_*` somente se a recuperação de senha por e-mail for utilizada. Como não há confirmação de e-mail, considerar proteções contra cadastros abusivos e e-mails de terceiros. A estrutura anterior de anúncios, o locale `ja-jp` de testes e os embeds do YouTube permanecem inalterados.

- O painel `/admin/moderation` permite manter termos ou expressões adicionais (`review` / `blocked`, todas as regiões ou uma região específica). Regras embutidas continuam protegendo as categorias principais, e termos isolados não devem ser confundidos com intenção.

## 29. Interações assíncronas sem recarga

- Curtidas em postagens (Home e página interna), curtidas em comentários, favoritos, comentários e respostas usam `fetch` e respostas JSON quando JavaScript está ativo, atualizando apenas os controles e comentários afetados. Preferências de interesses também são salvas sem sair da tela.
- O botão e os contadores mudam sem rolar a página ao topo, e os novos comentários/respostas aparecem na conversa atual. O comentário em revisão continua visível só para seu autor até a moderação; apenas comentários aprovados incrementam o contador público.
- Os endpoints mantêm redirecionamento tradicional para navegadores sem JavaScript, preservam autenticação/CSRF, e a moderação de texto permanece obrigatória no servidor. Erros de requisição devem ser exibidos sem reenviar automaticamente a ação e sem duplicar curtidas. O visual e os embeds aprovados não mudam.

## 30. Ações de visitantes e retorno após autenticação

- Na Home e nas postagens, ícones de curtir e comentar continuam visíveis para visitantes não autenticados, sem alterar o layout da Home. Ao tentar interagir, o visitante é direcionado ao login; ícones e contadores de comentários existentes seguem visíveis.
- Na postagem, curtidas, comentários, respostas e favoritos também mostram controles para visitantes, indicando necessidade de login. A leitura do conteúdo e dos comentários permanece pública.
- Após login por e-mail, criação de conta ou login Google, o visitante pode retornar à postagem e à seção de onde iniciou a interação; a ação não é executada automaticamente (o usuário precisa confirmar o clique novamente).
- O destino de retorno do login é validado no servidor: só é aceito um endereço do mesmo domínio, protocolo e locale público (incluindo o grupo `/en`). Destinos externos não podem ser usados para redirecionamentos abertos.
