                        </div>
                    </div>
                </div>
                
                <div id="footer" class="footer">
                    <p style="margin: 10px;">
                        <?php if (isset($footer_text)): ?>
                            <?php echo $footer_text; ?>
                        <?php else: ?>
                            MODULE CONNEXION &copy; 2025 | Développé par Remy
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    // Détection automatique du chemin de base
    $base_path = '';
    if (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) {
        $base_path = '../';
    }
    ?>
    <script src="<?php echo $base_path; ?>js/forum.js"></script>
    <?php if (isset($additional_scripts)): ?>
        <script><?php echo $additional_scripts; ?></script>
    <?php endif; ?>
</body>
</html>