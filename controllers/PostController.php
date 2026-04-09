<?php 

// namespace Controllers;

include_once(__DIR__ .'/Controller.php');

class PostController extends Controller
{
    
    //view post
    public function index(){

        $postQuery = "SELECT * FROM posts;";
        $result = $this->conn->query($postQuery);
        if ($result->num_rows > 0)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }


    //insert a new post
    public function savePost($value, $img, $desc)
    {
        $description = substr((string)$desc, 0, 1500);
        $title = substr((string)$value, 0, 45);
        $image =$img;

        if($_SERVER['REQUEST_METHOD']==='POST')
        {
            
            	if(empty($title)){
            		$errors[] = 'Post title is required';
            	} 
            	if(empty($description)){
            		$errors[] = 'Post description is required';
            	} 
            
                $imagePath = $this->image($image);
                
                // var_dump($imagePath);

            	if (empty($errors)) {
            	

                  $regster_qury ="INSERT INTO posts (title,image,description,create_date) VALUES('$title','$imagePath','$description', NOW())";
                //   var_dump($regster_qury); die;
                  $result = $this->conn->query($regster_qury);
                  return $result;

                }
             
        }
    }
        
          
   
         	
    public function edit($id)
    {
        $post_id =validateInput($this->conn,$id);

        $postQuery ="SELECT * FROM posts WHERE id='$post_id' LIMIT 1";
        $result = $this->conn->query($postQuery);
     
        if ($result->num_rows == 1)
        {
            $data = $result->fetch_assoc();
            return $data;
        }
        else{
            return false;
        }

    } 
    

    //update data
    public function upatePost($value, $img, $id, $desc)
    {
        $post_id = validateInput($this->conn,$id);

        $description = substr((string)$desc, 0, 1500);
        $title = substr((string)$value, 0, 45);
        $image = $img;

        if($_SERVER['REQUEST_METHOD']==='POST')
        {
            
            	if(empty($title)){
            		$errors[] = 'Post title is required';
            	} 
                if(empty($description)){
            		$errors[] = 'Post description is required';
            	} 
            
                $existingPost = $this->edit($post_id);
                $existingImage = $existingPost && isset($existingPost['image']) ? $existingPost['image'] : '';
                $imagePath = $this->image($image, $existingImage);

            	if (empty($errors)) {

                
            	   $regster_qury ="UPDATE posts  SET title='".$title."',image='".$imagePath."' ,description='".$description."'   where id='".$post_id."' LIMIT 1";
                  $result = $this->conn->query($regster_qury);
                  return $result;

                }
              
        }
    }
    //delete data
    public function deletePost($id){

        $postId = validateInput($this->conn,$id);

        $deletepost ="DELETE FROM posts WHERE id='$postId' LIMIT 1";
        $result = $this->conn->query($deletepost);
        if ($result){
            return true;
        }
        else{
            return false;
        }
    }

    // Generate a post description using an LLM, with local fallback when API is unavailable.
    public function generateDescriptionFromTitle($title)
    {
        $cleanTitle = trim((string)$title);
        if ($cleanTitle === '') {
            return '';
        }

        $llmText = $this->requestLlmDescription($cleanTitle);
        if ($llmText !== '') {
            return $llmText;
        }

        return $this->generateDescriptionFallback($cleanTitle);
    }

    private function requestLlmDescription($title)
    {
        if (!defined('LLM_API_KEY') || trim((string)LLM_API_KEY) === '') {
            return '';
        }

        if (!function_exists('curl_init')) {
            return '';
        }

        $seed = (string)mt_rand(100000, 999999);
        $prompt = "Write a unique, human-like blog post description for title: '{$title}'. " .
            "Use 2 short paragraphs, plain English, practical tone, and no markdown headings. " .
            "Add specific insights and avoid generic repetition. Variation seed: {$seed}.";

        $payload = [
            'model' => LLM_MODEL,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful content writer for blog drafts.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.9,
            'max_tokens' => 220,
        ];

        $ch = curl_init(LLM_ENDPOINT);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . LLM_API_KEY,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, defined('LLM_TIMEOUT_SECONDS') ? LLM_TIMEOUT_SECONDS : 20);

        $response = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode < 200 || $httpCode >= 300) {
            return '';
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            return '';
        }

        $text = '';
        if (isset($decoded['choices'][0]['message']['content'])) {
            $text = trim((string)$decoded['choices'][0]['message']['content']);
        }

        return substr($text, 0, 1500);
    }

    private function generateDescriptionFallback($title)
    {
        $openers = [
            "{$title} is growing rapidly and changing how people think about digital solutions.",
            "More teams are adopting {$title} to build reliable and modern systems.",
            "{$title} has become a practical skill for anyone building web or software products."
        ];

        $details = [
            "A good starting point is understanding the fundamentals, then applying them in small real-world tasks.",
            "Success comes from combining core concepts with consistent hands-on implementation.",
            "Learning by doing helps transform theory into confident and measurable progress."
        ];

        $benefits = [
            "With the right approach, it can improve performance, maintainability, and team productivity.",
            "It often leads to cleaner architecture, faster delivery cycles, and better user experience.",
            "Over time, this skill supports better decision-making and stronger technical outcomes."
        ];

        $closers = [
            "Start simple, review results often, and refine each iteration with feedback.",
            "Focus on practical outcomes and continue improving one step at a time.",
            "The key is steady progress, curiosity, and disciplined execution."
        ];

        return substr($openers[array_rand($openers)] . "\n\n" .
            $details[array_rand($details)] . ' ' . $benefits[array_rand($benefits)] . "\n\n" .
            $closers[array_rand($closers)], 0, 1500);
    }







         





}




?>











