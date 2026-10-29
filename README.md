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

- Estados Unidos: `/en-us/` — USD ($)
- Reino Unido: `/en-gb/` — GBP (£)
- Austrália: `/en-au/` — AUD (A$)
- Canadá: `/en-ca/` — CAD (C$)
- Japão: `/ja-jp/` — JPY (¥), mantido atualmente como locale de teste visual de regionalização.

É um único projeto, um único código-base e um único domínio. Não devem ser criados sites separados por região.

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
- canonical correto;
- `hreflang` entre versões regionais equivalentes;
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
- SEO title;
- SEO description.

Isso evita duplicar completamente uma publicação para cada país. Um registro em `posts` pode ter versões `en-us`, `en-gb`, `en-au` e `en-ca` em `post_localizations`.

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

### `suggestions`
Sugestões de pauta enviadas futuramente pelos visitantes.

## 7. Estados de publicação

O projeto trabalha somente com:

- `draft`: rascunho, não aparece publicamente;
- `published`: publicado, aparece publicamente quando a data de publicação é válida.

A data de publicação vem preenchida automaticamente no cadastro e pode ser alterada para uma data anterior quando necessário. Não existe status separado para agendamento ou arquivamento.

## 8. Regionalização

Configuração central em `config/localization.php`.

Atualmente:

- `en-us` -> `en_US` — Estados Unidos
- `en-gb` -> `en_GB` — Reino Unido
- `en-au` -> `en_AU` — Austrália
- `en-ca` -> `en_CA` — Canadá

Regras:

1. Não duplicar conteúdo quando não houver diferença real.
2. Permitir conteúdo regional específico quando necessário.
3. Manter URL, idioma e região coerentes.
4. Usar `hreflang` em páginas equivalentes.
5. Não obrigar troca de região apenas por IP.
6. Uma versão regional só recebe URL indexável quando sua localização estiver ativa.
7. Não existe fallback público entre países: sem versão regional própria, o conteúdo não aparece naquela região.
8. Imagem e vídeo também não usam mídia de outro país.
9. Categorias só aparecem quando possuem versão ativa para o locale atual.

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
- logs e backups em produção;
- nenhuma senha ou segredo versionado.

## 11. Vídeos e imagens

- Preferir vídeos oficiais incorporados.
- Não baixar e republicar trailers sem autorização.
- Registrar fonte quando necessário.
- Imagens e thumbnails devem possuir uso permitido.
- No futuro, vídeos devem usar carregamento sob demanda para preservar performance.

## 12. Publicidade

A estrutura deve permitir Google AdSense futuramente.

Regras:

- publicidade discreta;
- anúncio nunca deve parecer botão de reprodução;
- anúncios não podem destruir Core Web Vitals;
- conteúdo editorial deve continuar sendo o foco principal;
- consentimento e políticas aplicáveis devem ser respeitados.

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
- navegação principal, dark/light e estrutura `en-us` / `en-gb` / `en-au` / `en-ca`;
- SEO base, sitemap, canonical e `hreflang`;
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
- comentários com moderação;
- curtidas persistentes e visualizações deduplicadas;
- **Most Liked** e **Trending** por métricas reais;
- sugestões públicas de pauta e moderação administrativa;
- formulário de contato e caixa administrativa;
- painel interno de métricas;
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

- Visitantes não precisam criar conta para curtir ou comentar.
- A identificação técnica de visitante usa hash e não armazena IP puro no banco de reações/visualizações.
- Curtida funciona como alternância: curtir e remover curtida.
- Comentários novos começam como `pending` e só aparecem publicamente após aprovação.
- Comentários marcados como `spam` nunca aparecem no site.
- Visualizações repetidas do mesmo visitante em menos de 6 horas não aumentam o contador.
- Bots conhecidos não devem aumentar visualizações.
- Rotas públicas de comentário e curtida usam rate limiting.
- Métricas públicas são separadas por `locale`.
- **Trending Now** usa atividade recente do país; **Popular This Week** usa atividade dos últimos 7 dias do país.


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
- No formulário de postagem, `en-us`, `en-gb`, `en-au` e `en-ca` são apresentados como países: United States, United Kingdom, Australia e Canada.
- O controle “Use a specific version for this region” foi removido.
- O país padrão continua obrigatório.
- Um país adicional passa a ter versão ativa quando seu título é preenchido; se ficar sem título, a versão fica inativa.
- Existe apenas um registro principal em `posts`; cada país possui sua própria linha em `post_localizations`, com título, slug, resumo, sinopse, review e SEO.
- Nesta etapa, as versões regionais são preenchidas manualmente no admin. Não há API de IA nem tradução automática paga.
- Textos fixos da interface usam o sistema de tradução do Laravel conforme o locale da URL; conteúdo vindo do banco é responsabilidade editorial de cada versão regional.
- Os códigos técnicos de locale/região continuam existindo para URLs, `hreflang` e SEO internacional.

## 22. Regionalização da Home por país

- A Home continua sendo um único arquivo e mantém exatamente o mesmo layout visual.
- A URL define o locale ativo: `en-us`, `en-gb`, `en-au` ou `en-ca`.
- `SetLocale` aplica o locale correspondente e os textos fixos são resolvidos pelos arquivos JSON em `lang/`.
- Títulos de seções, busca, categorias padrão, descrições das categorias e SEO da Home variam conforme o país.
- As seis categorias padrão são regionalizadas pelo `slug`, evitando depender de um único nome global salvo em `categories`.
- Conteúdo editorial vindo de `post_localizations` continua sendo controlado no admin por país.
- Não duplicar a Home em quatro arquivos. Novos países devem ser adicionados em `config/localization.php` e receber seu arquivo de idioma.

## 23. Mídia por país no cadastro de postagem

- A mídia principal agora é cadastrada diretamente dentro de cada aba de país da postagem.
- Cada país pode escolher independentemente entre `Image` e `YouTube video`.
- Para YouTube, o admin recebe apenas a URL normal do vídeo; o sistema extrai o ID e monta o embed automaticamente.
- URLs de YouTube podem ser diferentes em `en-us`, `en-gb`, `en-au` e `en-ca`, permitindo trailer/vídeo específico para cada país.
- Imagens também podem ser diferentes por país e são armazenadas no disco `public`, dentro da pasta da postagem/locale.
- O banco continua usando a entidade `media`; não foi necessária nova migration.
- Para cada país, o formulário básico mantém uma mídia principal. Ao trocar imagem por YouTube (ou o contrário), a mídia principal daquele país é substituída.
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

## 22. Regras estritas por país

- Categorias seguem o mesmo modelo regional das postagens: uma categoria base e versões em `category_localizations`.
- Nome, slug, descrição e SEO de categoria são cadastrados por país.
- Se uma categoria não tiver versão ativa para a URL atual, ela não aparece nesse país.
- Se uma postagem não tiver `post_localizations` ativo para a URL atual, ela não aparece nesse país.
- Não existe fallback público de texto, categoria, imagem ou vídeo entre países.
- Mídia só pode ser usada quando `media.locale` corresponde exatamente ao país da URL.
- Curtidas, comentários e visualizações registram `locale`, evitando que métricas de um país alterem rankings de outro.
- Destaque editorial é definido por versão regional (`post_localizations.is_featured`).
- `Trending Now`: atividade recente do país (janela atual de 2 dias), ponderando visualizações, curtidas e comentários.
- `Latest Videos`: somente publicações do país com vídeo YouTube cadastrado para aquele país, ordenadas pela publicação mais recente.
- `Popular This Week`: atividade dos últimos 7 dias do país, ponderando visualizações, curtidas e comentários.
- Sitemap só deve incluir categorias e postagens que realmente tenham versão regional ativa.

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

