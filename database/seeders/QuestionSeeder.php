<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPhysicsQuiz();
        $this->seedChemistryQuiz();
        $this->seedBiologyQuiz();
        $this->seedEnvironmentQuiz();
        $this->seedLogicalQuiz();
        $this->seedScientificReasoningQuiz();
        $this->seedMythFactQuiz();
    }

    private function addQuestion(int $quizId, int $categoryId, string $text, array $options, int $correct, string $difficulty, string $temper, ?string $explanation = null, int $order = 0): void
    {
        $q = Question::create([
            'quiz_id'         => $quizId,
            'category_id'     => $categoryId,
            'question_text'   => $text,
            'explanation'     => $explanation,
            'difficulty'      => $difficulty,
            'points'          => match($difficulty) { 'easy' => 5, 'medium' => 10, 'hard' => 15 },
            'order'           => $order,
            'temper_category' => $temper,
        ]);
        foreach ($options as $i => $opt) {
            Option::create(['question_id' => $q->id, 'option_text' => $opt, 'is_correct' => $i === $correct, 'order' => $i]);
        }
    }

    private function seedPhysicsQuiz(): void
    {
        $quiz = Quiz::where('title', 'Fundamentals of Physics')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['What is the SI unit of force?', ['Newton', 'Joule', 'Watt', 'Pascal'], 0, 'easy', 'logical_thinking', 'Force = mass × acceleration. SI unit is Newton (N).'],
            ['Which law states that every action has an equal and opposite reaction?', ["Newton's First Law", "Newton's Second Law", "Newton's Third Law", "Law of Gravitation"], 2, 'easy', 'evidence_reasoning', "Newton's Third Law of Motion describes action-reaction pairs."],
            ['What is the speed of light in vacuum?', ['3×10⁸ m/s', '3×10⁶ m/s', '3×10¹⁰ m/s', '3×10⁴ m/s'], 0, 'easy', 'myth_fact', 'Speed of light c = 3×10⁸ m/s exactly.'],
            ['Energy can neither be created nor destroyed. This is:', ['Law of Conservation of Momentum', 'Law of Conservation of Energy', 'First Law of Thermodynamics', 'Both B and C'], 3, 'medium', 'evidence_reasoning', 'The First Law of Thermodynamics is equivalent to conservation of energy.'],
            ['A body in simple harmonic motion has maximum velocity at:', ['Extreme positions', 'Mean position', 'All positions equally', 'None of these'], 1, 'medium', 'problem_solving', 'At mean position, all PE converts to KE giving maximum velocity.'],
            ['What phenomenon explains why the sky appears blue?', ['Reflection', 'Refraction', 'Rayleigh Scattering', 'Diffraction'], 2, 'easy', 'myth_fact', 'Blue light scatters more due to its shorter wavelength — Rayleigh scattering.'],
            ['Which type of wave does NOT require a medium to travel?', ['Sound waves', 'Water waves', 'Electromagnetic waves', 'Seismic waves'], 2, 'easy', 'logical_thinking', 'EM waves travel through vacuum; all others need a medium.'],
            ['The work done by a force is zero when the angle between force and displacement is:', ['0°', '45°', '90°', '180°'], 2, 'medium', 'problem_solving', 'W = Fd cosθ; cos90° = 0.'],
            ['Absolute zero temperature equals:', ['-373°C', '-273°C', '0°C', '-100°C'], 1, 'easy', 'logical_thinking', 'Absolute zero = 0 K = -273.15°C.'],
            ['Which mirror is used in vehicle rear-view mirrors?', ['Concave mirror', 'Plane mirror', 'Convex mirror', 'Parabolic mirror'], 2, 'easy', 'myth_fact', 'Convex mirrors give a wider field of view, used in rear-view mirrors.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedChemistryQuiz(): void
    {
        $quiz = Quiz::where('title', 'Chemical Bonds & Reactions')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['What is the atomic number of Carbon?', ['4', '6', '8', '12'], 1, 'easy', 'logical_thinking', 'Carbon has 6 protons, so its atomic number is 6.'],
            ['Which type of bond involves sharing of electrons?', ['Ionic bond', 'Covalent bond', 'Metallic bond', 'Hydrogen bond'], 1, 'easy', 'evidence_reasoning', 'Covalent bonds form by sharing electron pairs between atoms.'],
            ['pH of pure water at 25°C is:', ['5', '7', '9', '14'], 1, 'easy', 'myth_fact', 'Pure water is neutral with pH = 7.'],
            ['What is the chemical formula of table salt?', ['KCl', 'NaCl', 'CaCl₂', 'MgCl₂'], 1, 'easy', 'logical_thinking', 'Table salt is Sodium Chloride (NaCl).'],
            ['Which gas is produced when dilute HCl reacts with zinc?', ['Oxygen', 'Carbon dioxide', 'Hydrogen', 'Nitrogen'], 2, 'medium', 'problem_solving', 'Zn + 2HCl → ZnCl₂ + H₂↑'],
            ['Rust is chemically known as:', ['Ferrous oxide', 'Ferric oxide', 'Hydrated iron(III) oxide', 'Iron carbonate'], 2, 'medium', 'myth_fact', 'Rust = Fe₂O₃·nH₂O — hydrated iron(III) oxide.'],
            ['Which element has the highest electronegativity?', ['Oxygen', 'Chlorine', 'Nitrogen', 'Fluorine'], 3, 'medium', 'evidence_reasoning', 'Fluorine is the most electronegative element (3.98 on Pauling scale).'],
            ['Avogadro\'s number is:', ['6.022×10²³', '3.14×10⁸', '1.6×10⁻¹⁹', '6.626×10⁻³⁴'], 0, 'easy', 'logical_thinking', 'One mole of any substance contains 6.022×10²³ particles.'],
            ['What type of reaction releases heat?', ['Endothermic', 'Exothermic', 'Photochemical', 'Reversible'], 1, 'easy', 'evidence_reasoning', 'Exothermic reactions release energy to surroundings.'],
            ['Which catalyst is used in the Haber process for making ammonia?', ['Platinum', 'Iron', 'Nickel', 'Vanadium pentoxide'], 1, 'medium', 'problem_solving', 'Finely divided iron is the catalyst in N₂ + 3H₂ → 2NH₃.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedBiologyQuiz(): void
    {
        $quiz = Quiz::where('title', 'Cell Biology & Genetics')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['What is the powerhouse of the cell?', ['Nucleus', 'Ribosome', 'Mitochondria', 'Golgi body'], 2, 'easy', 'myth_fact', 'Mitochondria produce ATP through cellular respiration.'],
            ['DNA stands for:', ['Deoxyribonucleic Acid', 'Diribonucleic Acid', 'Deoxyribose Nitrogen Acid', 'Dinucleic Acid'], 0, 'easy', 'logical_thinking', 'DNA = Deoxyribonucleic Acid, the hereditary material.'],
            ['Which blood group is called the universal donor?', ['A', 'B', 'AB', 'O'], 3, 'easy', 'myth_fact', 'O negative is the universal donor for red blood cells.'],
            ['How many chromosomes does a normal human cell have?', ['23', '44', '46', '48'], 2, 'easy', 'logical_thinking', 'Humans have 46 chromosomes (23 pairs) in somatic cells.'],
            ['Which organelle is responsible for protein synthesis?', ['Lysosome', 'Ribosome', 'Vacuole', 'Centriole'], 1, 'easy', 'evidence_reasoning', 'Ribosomes translate mRNA into proteins.'],
            ['Mendel\'s Law of Independent Assortment applies when genes are:', ['Linked', 'On the same chromosome', 'On different chromosomes', 'Recessive'], 2, 'medium', 'evidence_reasoning', 'Independent assortment occurs for genes on different chromosomes.'],
            ['Which molecule carries genetic information from DNA to ribosomes?', ['tRNA', 'mRNA', 'rRNA', 'snRNA'], 1, 'medium', 'problem_solving', 'mRNA (messenger RNA) transcribes DNA and carries the code to ribosomes.'],
            ['Osmosis is the movement of water from:', ['High concentration to low concentration', 'Low solute to high solute concentration', 'High pressure to low pressure', 'All of the above'], 1, 'medium', 'logical_thinking', 'Osmosis moves water from low solute to high solute concentration across a membrane.'],
            ['Which part of the brain controls voluntary movement?', ['Medulla oblongata', 'Cerebellum', 'Cerebrum', 'Hypothalamus'], 2, 'medium', 'evidence_reasoning', 'The cerebrum (motor cortex) controls voluntary movements.'],
            ['Photosynthesis occurs in:', ['Mitochondria', 'Chloroplasts', 'Nucleus', 'Ribosomes'], 1, 'easy', 'myth_fact', 'Chloroplasts contain chlorophyll and are the site of photosynthesis.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedEnvironmentQuiz(): void
    {
        $quiz = Quiz::where('title', 'Climate Science & Ecology')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['What is the primary greenhouse gas responsible for climate change?', ['Methane', 'Carbon dioxide', 'Nitrous oxide', 'Ozone'], 1, 'easy', 'evidence_reasoning', 'CO₂ from fossil fuels is the leading driver of anthropogenic climate change.'],
            ['The ozone layer protects Earth from:', ['UV radiation', 'X-rays', 'Infrared radiation', 'Radio waves'], 0, 'easy', 'myth_fact', 'Ozone (O₃) absorbs harmful UV-B and UV-C radiation from the Sun.'],
            ['Which gas is primarily responsible for ozone depletion?', ['CO₂', 'CH₄', 'CFCs', 'NO₂'], 2, 'medium', 'evidence_reasoning', 'Chlorofluorocarbons (CFCs) catalytically destroy ozone molecules.'],
            ['Acid rain is primarily caused by:', ['CO₂ and O₃', 'SO₂ and NOₓ', 'CO and CH₄', 'H₂S and NH₃'], 1, 'medium', 'problem_solving', 'SO₂ and NOₓ react with water to form H₂SO₄ and HNO₃.'],
            ['What does the term "biodiversity hotspot" mean?', ['A region with extreme temperatures', 'An area rich in species facing habitat threats', 'A volcanic region', 'A heavily polluted area'], 1, 'medium', 'logical_thinking', 'Hotspots have exceptional biodiversity under significant threat of habitat loss.'],
            ['The Paris Agreement aims to limit global warming to:', ['3°C above pre-industrial levels', '2°C above pre-industrial levels', '1°C above pre-industrial levels', '4°C above pre-industrial levels'], 1, 'medium', 'myth_fact', 'The Paris Agreement targets well below 2°C, ideally 1.5°C.'],
            ['Which renewable energy source contributes most to global electricity generation?', ['Solar power', 'Wind power', 'Hydropower', 'Geothermal'], 2, 'easy', 'evidence_reasoning', 'Hydropower remains the largest renewable electricity source globally.'],
            ['What is the main cause of eutrophication in water bodies?', ['Plastic pollution', 'Excess nutrients from fertilizers', 'Heavy metal contamination', 'Acid rain'], 1, 'medium', 'problem_solving', 'Nitrates and phosphates from fertilizers cause algal blooms leading to eutrophication.'],
            ['Carbon sequestration refers to:', ['Releasing carbon into atmosphere', 'Capturing and storing carbon dioxide', 'Carbon trading markets', 'Measuring carbon emissions'], 1, 'easy', 'logical_thinking', 'Carbon sequestration captures CO₂ from atmosphere or source and stores it.'],
            ['The "carbon footprint" is a measure of:', ['Land used by carbon industries', 'Greenhouse gases emitted by activities', 'Carbon stored in ecosystems', 'Carbon in ocean water'], 1, 'easy', 'evidence_reasoning', 'Carbon footprint = total GHG emissions caused directly/indirectly by a person/organization.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedLogicalQuiz(): void
    {
        $quiz = Quiz::where('title', 'Critical Reasoning Challenge')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['All scientists are curious. Ravi is a scientist. What can we conclude?', ['Ravi is not curious', 'Ravi is curious', 'Some scientists are not curious', 'Nothing can be concluded'], 1, 'easy', 'logical_thinking', 'Deductive syllogism: All A are B; Ravi is A; therefore Ravi is B.'],
            ['A study shows people who sleep more tend to live longer. What is the most logical conclusion?', ['Sleeping more causes longevity', 'There is a correlation between sleep and longevity', 'Everyone should sleep 12 hours', 'Sleep has no effect on health'], 1, 'medium', 'evidence_reasoning', 'Correlation does not imply causation. We can only conclude there is an association.'],
            ['If a hypothesis is tested and results consistently contradict it, a scientist should:', ['Ignore the results', 'Modify or reject the hypothesis', 'Only publish supporting data', 'Repeat until results match'], 1, 'easy', 'evidence_reasoning', 'Science requires updating beliefs based on evidence.'],
            ['Which is an example of confirmation bias?', ['Testing a hypothesis with controlled experiments', 'Only reading articles that support your belief', 'Peer reviewing research papers', 'Recording all experimental results'], 1, 'medium', 'myth_fact', 'Confirmation bias is the tendency to favor information that confirms existing beliefs.'],
            ['A researcher finds that ice cream sales correlate with drowning deaths. The best explanation is:', ['Ice cream causes drowning', 'Drowning increases ice cream sales', 'Hot weather increases both', 'The data is fabricated'], 2, 'medium', 'logical_thinking', 'This is a classic example of a confounding variable — summer/heat explains both.'],
            ['In a double-blind study, which of the following is true?', ['Only the researcher knows who got treatment', 'Neither participants nor researchers know who got treatment', 'Only participants know their treatment', 'Everyone knows the treatment given'], 1, 'medium', 'evidence_reasoning', 'Double-blind studies prevent bias in both administration and assessment.'],
            ['Which of these is NOT a characteristic of a good scientific hypothesis?', ['Testable', 'Falsifiable', 'Based on evidence', 'Cannot be proven wrong'], 3, 'medium', 'logical_thinking', 'A good hypothesis must be falsifiable — it must be possible to prove it wrong.'],
            ['If the same experiment gives different results each time, it lacks:', ['Validity', 'Reliability', 'Accuracy', 'Bias'], 1, 'easy', 'problem_solving', 'Reliability = consistency of results across repeated experiments.'],
            ['A medicine works in laboratory tests on cells but fails in human trials. What does this suggest?', ['The medicine is useless', 'Human biology differs from isolated cells', 'Lab tests are always wrong', 'More funding is needed'], 1, 'medium', 'problem_solving', 'In vitro results may not translate to in vivo due to biological complexity.'],
            ['What is the purpose of a control group in an experiment?', ['To increase sample size', 'To provide a baseline for comparison', 'To confuse participants', 'To reduce costs'], 1, 'easy', 'evidence_reasoning', 'The control group does not receive the treatment, serving as a reference baseline.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedScientificReasoningQuiz(): void
    {
        $quiz = Quiz::where('title', 'Scientific Method & Analysis')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['What is the first step of the scientific method?', ['Experiment', 'Hypothesis', 'Observation', 'Conclusion'], 2, 'easy', 'logical_thinking', 'Scientific method: Observation → Question → Hypothesis → Experiment → Conclusion.'],
            ['A hypothesis that can be tested and potentially proven wrong is called:', ['A theory', 'A law', 'Falsifiable', 'Anecdotal'], 2, 'easy', 'evidence_reasoning', 'Falsifiability is a key requirement for scientific hypotheses (Popper).'],
            ['Peer review in science serves to:', ['Make research expensive', 'Validate research through expert scrutiny', 'Delay publication', 'Promote the researcher'], 1, 'easy', 'evidence_reasoning', 'Peer review ensures quality, accuracy, and validity of published research.'],
            ['Which data presentation is best for showing trends over time?', ['Pie chart', 'Bar chart', 'Line graph', 'Scatter plot'], 2, 'easy', 'problem_solving', 'Line graphs clearly display changes and trends over a time period.'],
            ['The placebo effect refers to:', ['Medicine that has no active ingredient', 'Improvement due to belief in treatment effectiveness', 'Side effects of real medicine', 'Failure of treatment'], 1, 'medium', 'myth_fact', 'Placebo effect = improvement due to patient\'s expectation, not the actual treatment.'],
            ['What does "statistical significance" indicate?', ['Results are practically important', 'Results are unlikely due to chance', 'The sample size is large', 'Data has no error'], 1, 'medium', 'problem_solving', 'p<0.05 indicates less than 5% probability the result occurred by chance.'],
            ['Which is an example of qualitative data?', ['Temperature in Celsius', 'Number of bacteria colonies', 'Color of precipitate', 'Mass in grams'], 2, 'easy', 'logical_thinking', 'Qualitative data is descriptive and non-numerical, like color, texture, or smell.'],
            ['Replication in science means:', ['Copying another scientist\'s work', 'Repeating an experiment to verify results', 'Publishing multiple papers', 'Using the same equipment'], 1, 'easy', 'evidence_reasoning', 'Replication confirms that results are reliable and not a one-time occurrence.'],
            ['An experiment measures what it claims to measure. This is called:', ['Reliability', 'Accuracy', 'Validity', 'Precision'], 2, 'medium', 'logical_thinking', 'Validity = the degree to which an experiment measures what it intends to measure.'],
            ['Which statement best describes a scientific theory?', ['A guess about how something works', 'An untested hypothesis', 'A well-substantiated explanation supported by extensive evidence', 'An absolute truth'], 2, 'medium', 'myth_fact', 'Scientific theories are robust explanations backed by repeated testing and evidence.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }

    private function seedMythFactQuiz(): void
    {
        $quiz = Quiz::where('title', 'Myth vs Fact: Science Edition')->first();
        if (!$quiz) return;
        $cid = $quiz->category_id;
        $qid = $quiz->id;

        $questions = [
            ['Humans use only 10% of their brain. This is:', ['True — proven by brain scans', 'False — all brain regions are active', 'True — the rest is dormant', 'Partially true'], 1, 'easy', 'myth_fact', 'Neuroimaging shows virtually all brain areas are active. The 10% claim is a myth.'],
            ['Vaccines cause autism. This claim is:', ['Supported by peer-reviewed research', 'Based on a retracted fraudulent study', 'Scientifically proven', 'Under active investigation'], 1, 'easy', 'myth_fact', 'The 1998 Wakefield study was retracted. Extensive research confirms no link between vaccines and autism.'],
            ['Evolution is "just a theory," meaning it is unproven. This statement is:', ['Correct — theories are guesses', 'Incorrect — scientific theories are evidence-backed explanations', 'Correct — evolution has been disproven', 'Correct — it is debated by scientists'], 1, 'easy', 'evidence_reasoning', 'In science, a "theory" is a well-supported framework, not a guess.'],
            ['Lightning never strikes the same place twice. This is:', ['True', 'False — lightning often strikes the same place multiple times', 'True only for tall structures', 'Depends on weather conditions'], 1, 'easy', 'myth_fact', 'Lightning rods prove this wrong — they are struck repeatedly to protect buildings.'],
            ['Antibiotics are effective against viral infections like the common cold. This is:', ['True', 'False — antibiotics only work against bacteria', 'True if taken in large doses', 'True for some viruses'], 1, 'easy', 'evidence_reasoning', 'Antibiotics target bacterial cell structures. Viruses have no cell wall, so antibiotics are ineffective.'],
            ['The Great Wall of China is visible from space with the naked eye. This is:', ['True — confirmed by astronauts', 'False — it is too narrow to see from space', 'True from low Earth orbit', 'Partially true on clear days'], 1, 'medium', 'myth_fact', 'The Wall is about 5-8 meters wide — too narrow to see from space without optical aids.'],
            ['We have only 5 senses. This statement is:', ['Correct', 'Incorrect — humans have additional senses like balance and proprioception', 'Correct — verified by science', 'Debated'], 1, 'easy', 'logical_thinking', 'Humans have at least 20 senses including proprioception, thermoception, and vestibular sense.'],
            ['Dropping a penny from a skyscraper could kill someone. This is:', ['True — terminal velocity would be lethal', 'False — air resistance limits penny speed to a harmless level', 'True if dropped from high enough', 'Depends on coin weight'], 1, 'medium', 'problem_solving', 'A penny tumbles and reaches ~64 km/h terminal velocity. It stings but is not lethal.'],
            ['Blood in your veins is blue because it lacks oxygen. This is:', ['True — deoxygenated blood appears blue', 'False — blood is always red; veins appear blue through skin due to light absorption', 'True — CO₂ makes it blue', 'Partially true'], 1, 'medium', 'myth_fact', 'Blood is always red. Deoxygenated blood is dark red. Skin tissue absorbs red light, making veins look blue.'],
            ['The human body replaces all its cells every 7 years. This is:', ['Completely true', 'Partially true — cell replacement rates vary by cell type', 'False — cells never replace themselves', 'True only for skin cells'], 1, 'medium', 'evidence_reasoning', 'Some neurons last a lifetime; gut lining cells replace in days. The "7 years" is an oversimplification.'],
        ];

        foreach ($questions as $i => [$text, $opts, $correct, $diff, $temper, $expl]) {
            $this->addQuestion($qid, $cid, $text, $opts, $correct, $diff, $temper, $expl, $i);
        }
    }
}
